<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\DateRangeFilter;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('status')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        'draft' => 'secondary',
                        'published' => 'success',
                        'archived' => 'danger',
                    }),

                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->separator(', ')
                    ->sortable(),

                ImageColumn::make('images.image_path') // Display the images for each post
                    ->label('Post Images')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('F d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('User'),

                SelectFilter::make('category')
                    ->relationship('categories', 'name')
                    ->label('Category'),

                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),

            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconSize(IconSize::Large)->hiddenLabel()->badgeColor("red"),
                Tables\Actions\DeleteAction::make()->iconSize(IconSize::Large)->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->recordAction(Tables\Actions\ViewAction::class)
            ->recordUrl(null);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    protected static function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        }

        return $data;
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Group::make()->schema([
    //                 Section::make('Post Details')
    //                     ->schema([
    //                         TextEntry::make('title')
    //                             ->label('Title'),
    //                         TextEntry::make('slug')
    //                             ->label('Slug'),

    //                         TextEntry::make('content')
    //                             ->label('Content')
    //                             ->columnSpan('full'),

    //                         TextEntry::make('excerpt')
    //                             ->label('Short Excerpt')
    //                             ->columnSpan('full'),
    //                     ])
    //                     ->columns(2)

    //             ]),

    //             Group::make()->schema([
    //                 Section::make('Additional Info')
    //                     ->schema([
    //                         TextEntry::make('status')
    //                             ->label('Post Status'),

    //                         TextEntry::make('categories.name')
    //                             ->label('Categories')
    //                             ->columns(2),

    //                         ImageEntry::make('images.image')
    //                             ->label('Post Images')
    //                         // ->circular()
    //                         // ->stacked()
    //                         // ->limit(3)
    //                         // ->limitedRemainingText(),

    //                     ])
    //                     ->columns(1)
    //             ]),
    //         ]);
    // }
}
