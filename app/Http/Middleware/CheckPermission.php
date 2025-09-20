<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
{
    $user = $request->user();

    if (!$user || !$user->role) {
        abort(403, 'Bạn chưa được gán chức vụ.');
    }

    // ✅ Nếu là Super Admin thì luôn có quyền
    if ($user->id === 12 || $user->role->slug === 'super_admin') {
        return $next($request);
    }

    // Reload relation permissions để chắc chắn lấy quyền mới nhất
    $user->role->load('permissions');

    // Lấy danh sách slug của quyền
    $slugs = $user->role->permissions->pluck('slug');

    if (!$slugs->contains($permission)) {
        abort(403, 'Bạn không có quyền vào chức năng này.');
    }

    return $next($request);
}

}
