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

        // ✅ Admin luôn có quyền
        if ($user->role_id === 1) {
            return $next($request);
        }

        // ✅ Quản trị viên phụ (role_id = 2)
        if ($user->role_id === 2) {
            // Ví dụ chỉ cho phép các route có gắn role 2 hoặc role user
            if (in_array(2, $roles)) {
                return $next($request);
            }
            abort(403, 'Quản trị viên không có quyền truy cập route này.');
        }

        // ✅ Các role khác
        if (!in_array($user->role_id, $roles)) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
