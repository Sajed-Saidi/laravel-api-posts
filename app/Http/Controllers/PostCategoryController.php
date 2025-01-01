<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Post;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    use ApiResponseTrait;

    public function attachCategories(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $post->categories()->syncWithoutDetaching($validatedData['categories']);

        return $this->success(
            [],
            "Categories attached successfully!"
        );
    }

    public function detachCategory(Post $post, Category $category)
    {
        $post->categories()->detach($category->id);

        return $this->success(
            [],
            "Category detached successfully from the post!"
        );
    }
}
