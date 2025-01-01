<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostImageResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
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
    public function userPosts(Request $request)
    {
        $request->validate(
            [
                'user_id' => 'required|exists:users,id'
            ]
        );
        $query = Post::query()->with(['user.profile', 'categories', 'likes', 'comments'])->where('user_id', $request->user_id);

        if ($search = $request->input('search')) {
            $query->whereRaw(
                "MATCH(title, content, excerpt) AGAINST(? IN BOOLEAN MODE)",
                [$search]
            );
        }
        $posts = $query->latest()->paginate($request->input('per_page', 10));

        return $this->success(
            [

                'posts' => PostResource::collection($posts), // Transform using PostResource
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],

            ],
            "Posts fetched successfully!"
        );
    }
    public function index(Request $request)
    {
        $query = Post::with(['user.profile', 'categories', 'likes']);

        if ($search = $request->input('search')) {
            $query->whereRaw(
                "MATCH(title, content, excerpt) AGAINST(? IN BOOLEAN MODE)",
                [$search]
            );
        }

        $posts = $query->paginate($request->input('per_page', 10));

        return $this->success(
            [

                'posts' => PostResource::collection($posts), // Transform using PostResource
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],

            ],
            "Posts fetched successfully!"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $excerptLength = 60;

        if (strlen($validatedData['content']) > $excerptLength) {
            $validatedData['excerpt'] = substr($validatedData['content'], 0, $excerptLength) . '...';
        } else {
            $validatedData['excerpt'] = $validatedData['content'];
        }
        $validatedData['status'] = 'published';
        $validatedData['user_id'] = auth()->id();

        $post = Post::create($validatedData);

        if ($request->has('categories') && \count($request['categories']) > 0) {
            $post->categories()->syncWithoutDetaching($validatedData['categories']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $path = $this->storeImage($image);
                    $post->images()->create(['image_path' => $path]);
                } catch (\Exception $e) {
                    \Log::error("Image upload failed: " . $e->getMessage());
                }
            }
        }

        return $this->success(
            new PostResource($post),
            'Post Created Successfully!',
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
                $this->deleteImage($image->image_path);
                $image->delete();
            });

            foreach ($request->file('images') as $image) {
                try {
                    $path = $this->storeImage($image);
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
