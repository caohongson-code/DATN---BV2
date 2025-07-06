<?php

namespace App\Http\Controllers;

use App\Models\CartDetail;
use Illuminate\Http\Request;

class CartDetailController extends Controller
{
    public function destroy($id)
{
    $detail = CartDetail::findOrFail($id);
    $cartId = $detail->cart_id;
    $detail->delete();
    return redirect()->route('carts.show', $cartId)->with('success', 'Đã xoá sản phẩm khỏi giỏ');
}
}
