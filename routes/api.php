<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserProfileController;
use App\Mail\MyTestEmail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');

    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/user-profile', [UserProfileController::class, 'saveProfile'])->name('user-profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('posts', PostController::class);
    Route::get('/user-posts', [PostController::class, 'userPosts']);
    Route::prefix('posts')->group(function () {
        Route::post('/{post}/categories', [PostCategoryController::class, 'attachCategories'])->name('posts.attachCategories');
        Route::delete('/{post}/categories/{category}', [PostCategoryController::class, 'detachCategory'])->name('posts.detachCategory');

        Route::get('/{post}/comments', [CommentController::class, 'getPostsComments']);
        Route::post('/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
        Route::delete('/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');

        Route::post('/{post}/toggle-like', [LikeController::class, 'toggleLike'])->name('posts.likes.store');
    });

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}/posts', [CategoryController::class, 'categoryPosts'])->name('categories.posts');
});
