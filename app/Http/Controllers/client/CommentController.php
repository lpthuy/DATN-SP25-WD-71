<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return view('client.comment.index');
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->product_id = $id;
        $comment->user_id = auth()->user()->id ?? null;
        $comment->name = $request->name ?? null;
        $comment->email = $request->email ?? null;
        $comment->save();

        return redirect()->back()->with('success', 'Bình luận đã được gửi thành công!');
    }
}
