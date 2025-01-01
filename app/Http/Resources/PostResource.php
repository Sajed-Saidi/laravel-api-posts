<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\Like;
use App\Models\PostImage;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node\Expr\PostInc;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'userId' => $this->user_id,
            'isLiked' => $this->isLiked(),
            'user' => new UserResource($this->whenLoaded('user')),
            'images' => PostImageResource::collection($this->images),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likesCount' => $this->whenLoaded('likes', fn () => $this->likes->count(), 0),
            'createdAt' => Carbon::parse($this->created_at)->setTimezone('UTC')->format('Y-m-d H:i:s'),
            'updatedAt' => Carbon::parse($this->updated_at)->setTimezone('UTC')->format('Y-m-d H:i:s'),
        ];
    }

    public function isLiked(): bool
    {
        return Like::where('post_id', $this->id)
            ->where('user_id', Auth::id())
            ->exists();
    }
}
