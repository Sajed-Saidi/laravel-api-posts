<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostCategoryController;
use App\Http\Controllers\Api\PostController;
use App\Mail\MyTestEmail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    // User Registration ===DONE===
    Route::post('/login', 'login');
    Route::post('/register', 'register');

    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('posts', PostController::class)
        ->names(
            [
                'index' => 'posts.index',
                'store' => 'posts.store',
                'show' => 'posts.show',
                'update' => 'posts.update',
                'destroy' => 'posts.destroy',
            ]
        );

    Route::prefix('posts')->group(function () {
        Route::post('/{post}/categories', [PostCategoryController::class, 'attachCategories'])->name('posts.attachCategories');
        Route::delete('/{post}/categories/{category}', [PostCategoryController::class, 'detachCategory'])->name('posts.detachCategory');

        Route::post('/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
        Route::delete('/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');

        Route::post('/{post}/likes', [LikeController::class, 'store'])->name('posts.likes.store');
        Route::delete('/{post}/likes/{like}', [LikeController::class, 'destroy'])->name('posts.likes.destroy');
    });

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}/posts', [CategoryController::class, 'categoryPosts'])->name('categories.posts');
});
