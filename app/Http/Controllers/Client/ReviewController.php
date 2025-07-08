<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
public function store(Request $request)
{
    $request->validate([
    'product_variant_id' => 'required|exists:product_variants,id',
    'order_id' => 'required|exists:orders,id',
    'comment' => 'required|string|max:1000', // ✅ Đúng theo form
    'rating' => 'required|integer|min:1|max:5',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
]);


    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đánh giá.');
    }

    // Kiểm tra đã từng đánh giá sản phẩm này trong đơn chưa
    $exists = Review::where('account_id', $user->id)
        ->where('product_variant_id', $request->product_variant_id)
        ->where('order_id', $request->order_id)
        ->exists();

    if ($exists) {
        return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
    }

    // Xử lý lưu ảnh nếu có
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('reviews', 'public');
    }
// Tìm product_id từ variant (nếu cần)
$variant = ProductVariant::find($request->product_variant_id);
$productId = $variant?->product_id;

Review::create([
    'account_id' => $user->id,
    'product_variant_id' => $request->product_variant_id,
    'order_id' => $request->order_id,
    'comment' => $request->comment,
    'rating' => $request->rating,
    'image' => $imagePath,
    'product_id' => $productId, // thêm dòng này
]);


    return back()->with('success', 'Đánh giá của bạn đã được gửi.');
}


}
