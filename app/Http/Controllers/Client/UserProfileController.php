<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
     public function show(Request $request)
    {
        // Chỉ lưu URL trước nếu chưa có, và không phải trang hiện tại
        if (!$request->session()->has('user_return_url')) {
            $previous = url()->previous();

            if (!str_contains($previous, route('user.profile'))) {
                session(['user_return_url' => $previous]);
            }
        }

        return view('client.user.profile');
    }

    public function update(Request $request)
{
    $request->validate([
        'full_name'     => 'required|string|max:255',
        'phone'         => 'nullable|string|max:20',
        'gender'        => 'nullable|in:male,female',
        'date_of_birth' => 'nullable|date',
        'address'       => 'nullable|string|max:255',
    ]);

    // Map gender (male → 1, female → 0)
    $gender = null;
    if ($request->gender === 'male') {
        $gender = 1;
    } elseif ($request->gender === 'female') {
        $gender = 0;
    }

    DB::table('accounts')->where('id', Auth::id())->update([
        'full_name'     => $request->full_name,
        'phone'         => $request->phone,
        'gender'        => $gender,
        'date_of_birth' => $request->date_of_birth,
        'address'       => $request->address,
        'updated_at'    => now(),
    ]);

    $redirectBack = session('user_return_url') ?? route('user.profile');
    session()->forget('user_return_url');

    return redirect($redirectBack)->with('success', 'Cập nhật thông tin thành công!');
}


}