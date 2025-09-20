<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRoleForOrder
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Nếu user có role_id = 1 hoặc 2 thì không cho đặt hàng
        if ($user && in_array($user->role_id, [1, 2])) {
            return redirect()->back()->with('error', 'Tài khoản của bạn không được phép đặt hàng và sử dụng liên hệ .');
        }

        return $next($request);
    }
}
