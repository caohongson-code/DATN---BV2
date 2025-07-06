<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'order_id' => 'required|exists:orders,id',
            'content' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đánh giá.');
        }

        // Kiểm tra đã từng đánh giá sản phẩm này trong đơn chưa
        $exists = Review::where('account_id', $user->id)
            ->where('product_variant_id', $request->variant_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        // Tạo đánh giá mới
        Review::create([
            'account_id' => $user->id,
            'product_variant_id' => $request->variant_id,
            'order_id' => $request->order_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi.');
    }
}
