<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        $saved = Auth::user()->savedPromotions->pluck('id')->toArray();

        return view('client.user.promotion', compact('promotions', 'saved'));
    }

    public function save($id)
    {
        $user = Auth::user();
        if (!$user->savedPromotions()->where('promotion_id', $id)->exists()) {
            $user->savedPromotions()->attach($id);
        }

        return redirect()->back()->with('success', 'Đã lưu mã giảm giá.');
    }

    public function unsave($id)
    {
        Auth::user()->savedPromotions()->detach($id);

        return redirect()->back()->with('success', 'Đã bỏ lưu mã giảm giá.');
    }
}
