<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Xử lý lưu bình luận client gửi lên
  public function store(Request $request)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
        'image' => 'nullable|image|max:2048',
        'product_id' => 'required|exists:products,id',
    ]);

    $user = Auth::user();

    if (!$user) {
        return redirect()->route('taikhoan.login')
                         ->with('error', 'Vui lòng đăng nhập để bình luận.');
    }

    $comment = new Comment();
    $comment->account_id = $user->id;
    $comment->product_id = $request->product_id;
    $comment->comment = $request->comment;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('comments', 'public');
        $comment->image = $path;
    }

    $comment->save();

    // Redirect về trang sản phẩm, để show() xử lý lấy bình luận
    return redirect()
        ->route('product.show', ['id' => $request->product_id])
        ->with('success', 'Gửi bình luận thành công!');
}


}
