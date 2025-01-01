<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return $this->success(
            CategoryResource::collection($categories),
            "Categories Fetched Successfully!"
        );
    }

    /**
     * Display the specified resource.
     */
    public function categoryPosts(string $id)
    {
        $posts = Category::findOrFail($id)->posts()->get();

        if ($posts->count() == 0) {
            return $this->success(
                [
                    'categories' => []
                ],
                "No Posts Found For This Category!",
            );
        }

        return $this->success(
            [
                'posts' => PostResource::collection($posts)
            ],
            "Posts Fetched Successfully!"
        );
    }
}
