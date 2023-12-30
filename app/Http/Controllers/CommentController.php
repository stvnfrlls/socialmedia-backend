<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function index()
    {
        $response_data = Comment::with('user', 'post')->get();

        return response()->json($response_data);
    }

    public function store(CommentRequest $request)
    {
        $response_data = Comment::create($request->all());

        if ($response_data) {
            $message = 'Commented Successfully';
        } else {
            $message = 'Error occurred when commenting';
        }

        return response()->json($message);
    }

    public function show(Comment $comment)
    {
        return response()->json($comment);
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        if ($comment) {
            $comment->comment = $request->input('comment');
            $comment->save();

            $message = 'Comment updated successfully';
        } else {
            $message = 'Comment not found';
        }

        return response()->json($message);
    }

    public function destroy(Comment $comment)
    {
        if ($comment) {
            $comment->delete();

            $message = 'Comment deleted successfully';
        } else {
            $message = 'Comment not found';
        }

        return response()->json($message);
    }
}
