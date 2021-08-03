<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostController extends Controller
{
    /**
     * Display a listing of all posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return JsonResource::collection(Post::all());
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $this->validateSlug($request->input('slug'));

        if (!$validated) {
            return response()->json([
                'status' => 405,
                'message' => 'Slug is not allowed. Only lowercase characters, numbers and - _ are allowed'
            ]);
        }

        $newPost = new Post;

        $newPost->slug = $request->input('slug');
        $newPost->title = $request->input('title');
        $newPost->content = $request->input('content');
        $newPost->enabled = $request->input('enabled');

        $newPost->save();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'post' => $newPost
        ]);
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(Post $post)
    {
        return JsonResource::collection(Post::find($post->id));
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        $validated = $this->validateSlug($request->input('slug'));

        if (!$validated) {
            return response()->json([
                'status' => 405,
                'message' => 'Slug is not allowed. Only lowercase characters, numbers and - _ are allowed'
            ]);
        }

        $updatePost = Post::find($post->id);

        $updatePost->slug = $this->validateSlug($request->input('slug'));
        $updatePost->title = $request->input('title');
        $updatePost->content = $request->input('content');
        $updatePost->enabled = $request->input('enabled');

        $updatePost->save();

        return response()->json([
            'status' => 200,
            'message' => 'Post has been updated',
            'post' => [
                $updatePost
            ]
        ]);
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        Post::find($post->id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Post has been deleted'
        ]);
    }

    /**
     * Enable the specified post in storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable($id): \Illuminate\Http\JsonResponse
    {
        $enablePost = Post::find($id);

        $enablePost->enabled = 1;

        $enablePost->save();

        return response()->json([
            'status' => 200,
            'message' => 'Post has been enabled',
            'post' => $enablePost
        ]);
    }

    /**
     * Disable the specified post in storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable($id): \Illuminate\Http\JsonResponse
    {
        $enablePost = Post::find($id);

        $enablePost->enabled = 0;

        $enablePost->save();

        return response()->json([
            'status' => 200,
            'message' => 'Post has been disabled',
            'post' => $enablePost
        ]);
    }


    /**
     * Verify if the slug matches the regular expression.
     *
     * @param $slug
     * @return bool
     */
    private function validateSlug($slug): bool
    {
        if (preg_match('/^[0-9a-z-_]+$/', $slug)) {
            return true;
        } else {
            return false;
        }
    }
}
