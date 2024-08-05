<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // Create a new post
    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);

        return response()->json($post, 201);
    }

    // Create a comment on a post
    public function commentOnPost(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($postId);

        $comment = Comment::create([
            'content' => $request->input('content'),
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json($comment, 201);
    }
}
