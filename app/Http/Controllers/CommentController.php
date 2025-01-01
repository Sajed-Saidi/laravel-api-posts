<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\ApiResponseTrait;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponseTrait;

    public function getPostsComments(Request $request, Post $post)
    {
        $comments = Comment::with('user.profile')->where('post_id', $post->id)->get();

        return $this->success(
            CommentResource::collection($comments),
            "Comments retrieved successfully!",

        );
    }
    public function store(StoreCommentRequest $request, Post $post)
    {
        $validatedData = $request->validated();

        $comment = Comment::create([
            'content' => $validatedData['content'],
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        $comment->load(['user']);

        return $this->success(
            new CommentResource(($comment)),
            "Comment Created Successfully!",
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
