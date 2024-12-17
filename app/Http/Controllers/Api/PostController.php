<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostImageResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Api\Post;
use App\Traits\ApiResponseTrait;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class PostController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->posts()->with(['categories', 'likes', 'comments']);

        if ($search = $request->input('search')) {
            $query->whereRaw(
                "MATCH(title, content, excerpt) AGAINST(? IN BOOLEAN MODE)",
                [$search]
            );
        }

        $posts = $query->paginate($request->input('per_page', 10));

        if ($posts->count() == 0) {
            return $this->success(
                [
                    'posts' => []
                ],
                "Posts Not Found",
                404
            );
        }

        return $this->success(
            [
                'posts' => PostResource::collection($posts),
            ],
            "Posts fetched successfully!"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // dd($request->hasFile('images'));
        $validatedData = $request->validated();

        $validatedData['slug'] = Str::slug($validatedData['slug']);

        $post = Post::create($validatedData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $uniqueName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('images/posts', $uniqueName, 'public');
                    $post->images()->create(['image_path' => $path]);
                } catch (\Exception $e) {
                    \Log::error("Image upload failed: " . $e->getMessage());
                }
            }
        }

        return $this->success(
            [
                'post' => new PostResource($post),
            ],
            'Post created successfully!',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $post = Auth::user()->posts()->with(['categories', 'likes', 'comments'])->findOrFail($id);

        return $this->success(
            [
                'post' => new PostResource($post),
            ],
            'Post found'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Auth::user()->posts()->with(['categories', 'likes', 'comments'])->findOrFail($id);

        $validatedData = $request->validated();

        // for Patch
        if (isset($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['slug']);
        }

        $post->update($validatedData);

        if ($request->hasFile('images')) {
            $post->images()->each(function ($image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            });

            foreach ($request->file('images') as $image) {
                try {
                    $uniqueName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('images/posts', $uniqueName, 'public');
                    $post->images()->create(['image_path' => $path]);
                } catch (\Exception $e) {
                    \Log::error("Image upload failed: " . $e->getMessage());
                }
            }
        }

        return $this->success(
            [
                'post' => new PostResource($post)
            ],
            'Post updated successfully!',
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Auth::user()->posts()->findOrFail($id);

        $post->delete();

        return $this->success(
            null,
            'Deleted Successfully!'
        );
    }
}
