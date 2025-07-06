<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('client.user.profile');
    }

    public function edit()
    {
        return view('client.user.profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender'        => 'nullable|in:1,2',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();

        DB::table('accounts')->where('id', $userId)->update([
            'full_name'     => $request->input('full_name'),
            'phone'         => $request->input('phone'),
            'gender'        => $request->input('gender'),
            'date_of_birth' => $request->input('date_of_birth'),
            'address'       => $request->input('address'),
            'updated_at'    => now(),
        ]);

        return redirect()->route('user.profile')->with('success', 'Cập nhật thông tin thành công!');
    }
}
