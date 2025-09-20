{{-- @extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Phân quyền cho chức vụ</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <form action="{{ route('roles.permissions.assign') }}" method="GET" class="mb-4">
        <label for="role_id">Chọn chức vụ</label>
        <select name="role_id" id="role_id" class="form-control" onchange="this.form.submit()">
            <option value="">-- Chọn chức vụ --</option>
            @foreach($roles as $role)
                @if($role->id != 3)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->role_name }}
                    </option>
                @endif
            @endforeach
        </select>
    </form>


    @if($selectedRole)
        <form action="{{ route('roles.permissions.storeAssign') }}" method="POST">
            @csrf
            <input type="hidden" name="role_id" value="{{ $selectedRole->id }}">

            <div class="mb-3">
                <label>Chọn quyền</label>
                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       id="perm{{ $permission->id }}"
                                       {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm{{ $permission->id }}">
                                    {{ $permission->permission_name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Lưu phân quyền</button>
        </form>
    @endif
</div>
@endsection --}}
@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Phân quyền cho chức vụ</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form chọn role --}}
            <form action="{{ route('roles.permissions.assign') }}" method="GET" class="mb-4">
                <label for="role_id" class="form-label">Chọn chức vụ</label>
                <select name="role_id" id="role_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Chọn chức vụ --</option>
                    @foreach($roles as $role)
                        @if($role->id != 3) {{-- loại bỏ người dùng thường --}}
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </form>

            {{-- Nếu đã chọn role --}}
            @if($selectedRole)
                <form action="{{ route('roles.permissions.storeAssign') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $selectedRole->id }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chọn quyền</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               id="perm{{ $permission->id }}"
                                               {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm{{ $permission->id }}">
                                            {{ $permission->permission_name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Lưu phân quyền
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
