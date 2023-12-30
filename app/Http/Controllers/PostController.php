<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function index()
    {
        $response_data = Post::orderBy('posts.created_at', 'desc')
            ->with('user', 'comment')
            ->get();

        foreach ($response_data as $data) {
            foreach ($data->comment as $comment) {
                $comment = $comment->user->name;
            }
        }

        $transformedData = $response_data->map(function ($data) {
            return $this->transformPostData($data);
        });

        return response()->json($transformedData);
    }

    public function store(PostRequest $request)
    {
        $response_data = Post::create($request->all());

        if ($response_data) {
            $message = 'Posted Successfully';
        } else {
            $message = 'Error occurred when posting';
        }

        return response()->json($message);
    }

    public function show($post)
    {
        $response_data = Post::where('id', $post)->with('user')->get();

        $transformedData = $response_data->map(function ($data) {
            return $this->transformPostData($data);
        });

        return response()->json($transformedData);
    }

    public function list($authorId)
    {
        $response_data = Post::where('user_id', $authorId)
            ->with('user', 'comment')
            ->latest('created_at')
            ->get();

        foreach ($response_data as $data) {
            foreach ($data->comment as $comment) {
                $comment = $comment->user->name;
            }
        }

        $transformedData = $response_data->map(function ($data) {
            return $this->transformPostData($data);
        });

        return response()->json($transformedData);
    }

    public function update(PostRequest $request, Post $post)
    {
        $post = Post::findOrFail($post->id);

        if ($post) {
            $post->post = $request->input('post');
            $post->save();

            $message = 'Post updated successfully';
        } else {
            $message = 'Post not found';
        }

        return response()->json($message);
    }

    public function destroy(Post $post)
    {
        $post = Post::findOrFail($post->id);

        if ($post) {
            $post->delete();

            $message = 'Post deleted successfully';
        } else {
            $message = 'Post not found';
        }

        return response()->json($message);
    }

    public function transformPostData($data)
    {
        $data = $data->toArray();

        $postDataKeys = [
            'user_id',
            'updated_at',
        ];

        $modifiedPostData = array_diff_key($data, array_flip($postDataKeys));

        $userKeys = [
            'email',
            'email_verified_at',
            'created_at',
            'updated_at'
        ];

        $modifiedPostData['author_id'] = $data['user']['id'];
        $modifiedPostData['author'] = $data['user']['name'];
        $modifiedPostData['user'] = array_diff_key($data['user'], array_flip($userKeys));
        unset($modifiedPostData['user']);

        $commentKeys = [
            'created_at',
            'updated_at',
        ];

        $modifiedPostData['comments'] = [];

        if (isset($data['comment'])) {
            foreach ($data['comment'] as $comment) {
                $filteredComment = array_diff_key($comment, array_flip($commentKeys));

                $filteredComment['user'] = array_diff_key($comment['user'], array_flip($userKeys));
                $filteredComment['name'] = $filteredComment['user']['name'];

                unset($filteredComment['user']);

                $modifiedPostData['comments'][] = $filteredComment;
            }


            unset($modifiedPostData['comment']);
        }

        return $modifiedPostData;
    }
}
