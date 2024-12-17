<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLikeRequest;
use App\Models\Api\Like;
use App\Models\Api\Post;
use App\Traits\ApiResponseTrait;
use Auth;
use Request;

class LikeController extends Controller
{
    use ApiResponseTrait;

    public function store(Post $post)
    {
        $existingLike = Like::where(
            [
                'user_id' => Auth::id(),
                'post_id' => $post->id
            ]
        )
            ->first();

        if ($existingLike) {
            return $this->error(null, "You have already liked this post.", 400);
        }

        $like = Like::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        return $this->success(
            [
                'like' => $like
            ],
            "Like Created Successfully!",
            201
        );
    }

    public function destroy(Post $post, Like $like)
    {
        // Check if the like belongs to the correct post
        if ($like->post_id !== $post->id) {
            return $this->error(null, "Like not found for this post", 404);
        }

        // Ensure the current user is the one who liked the post
        if ($like->user_id !== Auth::id()) {
            return $this->error(null, "You are not authorized to delete this Like", 403);
        }

        // Delete the like
        $like->delete();

        return $this->success(
            null,
            "Like Deleted Successfully!",
        );
    }
}
