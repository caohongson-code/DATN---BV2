<?php

namespace App\Http\Controllers;

use App\Mail\ThongBaoTaoTaiKhoan;
use App\Models\Account;
use App\Models\Product;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::with('role')->whereIn('role_id', [1, 2]);

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $accounts->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $listQT = $accounts->orderByDesc('id')->paginate(3)->withQueryString();
        $admin = Auth::user();

        return view('admin.accounts.index', compact('listQT', 'admin'));
    }

    public function create()
    {

        $roles = Role::all();
        return view('admin.accounts.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $message = [
            'role_id.required'   => 'Vui lòng chọn quyền.',
            'role_id.exists'     => 'Quyền không tồn tại.',
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.email'        => 'Email không đúng định dạng.',
            'email.unique'       => 'Email đã tồn tại.',
            'gender.required'    => 'Vui lòng chọn giới tính.',
            'gender.in'          => 'Giới tính không hợp lệ.',
        ];

        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email',
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'nullable|string',
        ], $message);

        try {
            DB::beginTransaction();

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar')->store('uploads/quantri', 'public');
            }

            $data['password'] = bcrypt('1234');
             Account::create($data);

            DB::commit();
            return redirect()->route('accounts.index')->with('success', 'Tạo tài khoản thành công!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('accounts.create')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $roles = Role::all();

        return view('admin.accounts.edit', compact('account', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email,' . $id,
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $account = Account::findOrFail($id);
            $account->update($data);

            DB::commit();
            return redirect()->route('accounts.index')->with('success', 'Cập nhật thành công!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('accounts.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);

        $filePath = $account->avatar;
        $account->delete();

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return redirect()->route('accounts.index')->with('success', 'Xoá thành công!');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $account = Account::where('email', $request->email)->first();

        if ($account && Hash::check($request->password, $account->password)) {
            // Sử dụng guard 'account' để đăng nhập
            Auth::guard('account')->login($account);

            if ($account->role_id == 1) {
                // Admin
                session(['admin_id' => $account->id]);
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Đăng nhập quản trị thành công!');
            } elseif ($account->role_id == 2) {
                // Quản trị viên
                session(['admin_id' => $account->id]);
                return redirect()->route('products.index')
                    ->with('success', 'Đăng nhập quản trị viên thành công!');
            } else {
                // User thường
                return redirect()->route('home')
                    ->with('success', 'Đăng nhập người dùng thành công!');
            }
        }

        return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');
    }



    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->is('admin/*')) {
        return redirect()->route('login')->with('success', 'Đăng xuất admin thành công!');
    }

    return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
}
    public function register(Request $request)
    {
        $request->validate([
    'full_name' => 'required|string|max:255',
    'email'     => 'required|email|unique:accounts,email',
    'password'  => 'required|string|min:6|confirmed',
    'password_confirmation' => 'required|string|min:6',
], [
    'full_name.required' => 'Vui lòng nhập họ tên.',
    'email.required'     => 'Vui lòng nhập email.',
    'email.email'        => 'Email không đúng định dạng.',
    'email.unique'       => 'Email đã tồn tại.',
    'password.required'  => 'Vui lòng nhập mật khẩu.',
    'password.min'       => 'Mật khẩu phải từ 6 ký tự.',
    'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
    'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
]);


        try {
            DB::beginTransaction();
            $plainPassword = $request->password;
            $data = $request->only(['full_name', 'email', 'password']);
            $data['password'] = bcrypt($plainPassword);
            $data['role_id'] = 3; // Người dùng mặc định

            $user = Account::create($data);
            Mail::to($user->email)->send(new ThongBaoTaoTaiKhoan($user, $plainPassword));
            DB::commit();
            return redirect()->route('login')->with('success', 'Đăng ký thành công!');
            // return redirect()->route('taikhoan.showLoginForm')->with('success', 'Đăng ký thành công!');
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

        // Hiển thị thông tin cá nhân admin
        public function show()
        {
            $account = Auth::user();
            return view('admin.accounts.show', compact('account'));
        }
    // Cập nhật thông tin cá nhân
    public function updateAdminProfile(Request $request)
    {

            $admin = Auth::user();

            $data = $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:10',
                'gender' => 'nullable|in:0,1',
                'date_of_birth' => 'nullable|date',
                'address' => 'nullable|string|max:255',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            ]);

            // Xử lý ảnh
            if ($request->hasFile('avatar')) {
                // Xoá ảnh cũ nếu có
                if ($admin->avatar && Storage::exists($admin->avatar)) {
                    Storage::delete($admin->avatar);
                }

                $path = $request->file('avatar')->store('avatars', 'public'); // lưu vào storage/app/public/avatars
                $data['avatar'] = $path;
            }

        $admin = Account::find(Auth::id());
        $admin->update($data);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // Cập nhật mật khẩu
    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $admin = Auth::user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }
        $admin = Account::find(Auth::id());
        $admin->update(['password' => bcrypt($request->new_password)]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
    public function showForgotForm()
{
    return view('admin.auth.forgot-password'); // dùng chung form cho admin & khách
}

public function sendResetLink(Request $request)
{
    // Validate email
     $request->validate([
        'email' => 'required|email',
    ], [
        'email.required' => 'Vui lòng nhập email',
        'email.email'    => 'Email không đúng định dạng',
    ]);
    // Gửi link reset password qua broker 'accounts'
    $status = Password::broker('accounts')->sendResetLink(
        $request->only('email')
    );

    // Kiểm tra kết quả
    if ($status === Password::RESET_LINK_SENT) {
        return back()->with('success', __('Link đặt lại mật khẩu đã được gửi vào email!'));
        // Hiển thị: "Link đặt lại mật khẩu đã được gửi vào email!"
    }

    return back()->withErrors([
        'email' => __('Chúng tôi không thể tìm thấy người dùng nào có địa chỉ email đó.')
        // Hiển thị: "Chúng tôi không thể tìm thấy người dùng nào có địa chỉ email đó."
    ]);
}

public function showResetForm(Request $request, $token = null)
{
    return view('admin.auth.reset-password')->with([
        'token' => $token,
        'email' => $request->email, // email có thể được truyền từ query string
    ]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:6|confirmed',
        'token'    => 'required',
    ], [
        'email.required'    => 'Vui lòng nhập email',
        'email.email'       => 'Email không đúng định dạng',
        'password.required' => 'Vui lòng nhập mật khẩu mới',
        'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự',
        'password.confirmed'=> 'Mật khẩu nhập lại không khớp',
        'token.required'    => 'Thiếu mã token đặt lại mật khẩu',
    ]);

    $status = Password::broker('accounts')->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công, vui lòng đăng nhập.')
        : back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn.']);
}

}
