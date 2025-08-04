<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
{
    $account = Auth::user();

    // Nếu chưa có ví thì tạo mới
    $wallet = $account->wallet;
    if (!$wallet) {
        $wallet = $account->wallet()->create([
            'balance' => 0,
        ]);
    }

    $wallet->load('transactions');

    return view('client.user.wallet', compact('wallet'));
}

}
