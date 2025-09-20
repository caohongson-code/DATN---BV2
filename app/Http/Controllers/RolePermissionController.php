<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    // Hiển thị form gán quyền cho role
    public function assign(Request $request)
    {
        $roles = Role::all();              // Lấy tất cả role
        $selectedRole = null;
        $rolePermissions = [];

        // Lấy role được chọn từ query string
        if ($request->filled('role_id')) {
            $selectedRole = Role::with('permissions')->find($request->role_id);
            if ($selectedRole) {
                // Lấy ID quyền đã gán cho role
                $rolePermissions = $selectedRole->permissions->pluck('id')->toArray();
            }
        }

        $permissions = Permission::all();  // Lấy tất cả quyền

        return view('admin.assign.assign', compact('roles', 'permissions', 'selectedRole', 'rolePermissions'));
    }

    // Lưu quyền cho role
    // public function storeAssign(Request $request)
    // {
    //     $request->validate([
    //         'role_id' => 'required|exists:roles,id',
    //         'permissions' => 'array',          // Mảng quyền (có thể trống)
    //         'permissions.*' => 'exists:permissions,id',
    //     ]);
    //     // dd($request->permissions);
    //     $role = Role::findOrFail($request->role_id);

    //     // Gán quyền mới, sync() sẽ thay thế quyền cũ
    //     $role->permissions()->sync($request->permissions ?? []);

    //     // Redirect về trang assign với role_id, view sẽ load quyền mới
    //     return redirect()
    //         ->route('roles.permissions.assign', ['role_id' => $role->id])
    //         ->with('success', 'Cập nhật phân quyền thành công!');
    // }
    public function storeAssign(Request $request)
{
    $request->validate([
        'role_id' => 'required|exists:roles,id',
        'permissions' => 'array',          // Mảng quyền (có thể trống)
        'permissions.*' => 'exists:permissions,id',
    ]);

    $role = Role::findOrFail($request->role_id);

    // 🚫 Không cho sửa role Super Admin
    if ($role->slug === 'super_admin') {
        return redirect()
            ->route('roles.permissions.assign', ['role_id' => $role->id])
            ->with('error', 'Không thể thay đổi phân quyền của Super Admin!');
    }

    // ✅ Với các role khác thì vẫn gán quyền bình thường
    $role->permissions()->sync($request->permissions ?? []);

    return redirect()
        ->route('roles.permissions.assign', ['role_id' => $role->id])
        ->with('success', 'Cập nhật phân quyền thành công!');
}

}
