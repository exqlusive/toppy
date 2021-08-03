<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of all posts.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|object
     */
    public function index()
    {
        return JsonResource::collection(Post::all())->response()->setStatusCode(200);
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
        ])->setStatusCode(201);
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|object
     */
    public function show(Post $post)
    {
        return response()->json(Post::find($post->id))->setStatusCode(200);
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
            ])->setStatusCode(405);
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
        ])->setStatusCode(200);
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
        ])->setStatusCode(200);
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
        ])->setStatusCode(200);
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
        ])->setStatusCode(200);
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
