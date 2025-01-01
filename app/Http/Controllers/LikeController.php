<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLikeRequest;
use App\Models\Like;
use App\Models\Post;
use App\Traits\ApiResponseTrait;
use Auth;
use Request;

class LikeController extends Controller
{
    use ApiResponseTrait;

    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Unlike the post
            $like->delete();
            return $this->success(
                [
                    'liked' => false
                ],
                'Post unliked'
            );
        } else {
            // Like the post
            $post->likes()->create(['user_id' => $user->id]);
            return $this->success(
                [
                    'liked' => true
                ],
                'Post liked'
            );
        }
    }
}
