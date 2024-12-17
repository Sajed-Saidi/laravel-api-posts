<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Api\Comment;
use App\Models\Api\Post;
use App\Traits\ApiResponseTrait;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponseTrait;

    public function store(StoreCommentRequest $request, Post $post)
    {
        $validatedData = $request->validated();

        $comment = Comment::create([
            'content' => $validatedData['content'],
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        return $this->success(
            [
                'comment' => $comment
            ],
            "Comment Created Successfully!",
            201
        );
    }


    public function destroy(Post $post, Comment $comment)
    {
        if ($comment->post_id !== $post->id) {
            return $this->error(null, "Comment not found for this post", 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return $this->error(null, "You are not authorized to delete this comment", 403);
        }

        $comment->delete();

        return $this->success(
            null,
            "Comment Deleted Successfully!",
        );
    }
}
