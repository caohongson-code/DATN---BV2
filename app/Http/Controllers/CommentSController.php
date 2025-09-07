<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentSController extends Controller
{
    // Hiển thị danh sách bình luận
    public function index()
{
    $comments = Comment::with('product')->latest()->paginate(10);
    return view('admin.comment.index', compact('comments'));
}

    // Ẩn bình luận
    public function hide($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = 0; // 0 = ẩn
        $comment->save();

        return redirect()->back()->with('success', 'Đã ẩn bình luận thành công.');
    }

    // Hiện bình luận
    public function showComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = 1; // 1 = hiện
        $comment->save();

        return redirect()->back()->with('success', 'Đã hiển thị bình luận thành công.');
    }
}
