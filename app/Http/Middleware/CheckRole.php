<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // ✅ Admin luôn có quyền truy cập mọi route
        if ($user->role_id === 1) {
    return $next($request); // Admin luôn có quyền
}

if (!in_array($user->role_id, $roles)) {
    abort(403, 'Bạn không có quyền truy cập trang này.');
}

        return $next($request);
    }
}
