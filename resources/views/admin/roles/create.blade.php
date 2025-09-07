@extends('admin.layouts.app')

@section('title', 'Create New Role')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0 rounded-4 w-100"
         style="background: #fff; border: 1px solid rgba(0,0,0,0.05);">

        {{-- Header trắng, chữ đen --}}
        <div class="card-header bg-white text-dark rounded-top-4 px-4 py-3 border-bottom">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-user-shield me-2"></i> Thêm chức vụ
            </h4>
        </div>

        {{-- Body --}}
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('roles.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                {{-- Role Name --}}
                <div class="mb-4">
                    <label for="role_name" class="form-label fw-semibold">
                        Role Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="role_name" id="role_name"
                           value="{{ old('role_name') }}"
                           class="form-control form-control-lg rounded-pill shadow-sm border-0 @error('role_name') is-invalid @enderror"
                           style="background: #f8faff;">
                    @error('role_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="form-control form-control-lg rounded-3 shadow-sm border-0 @error('description') is-invalid @enderror"
                              style="background: #f8faff;">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm border-0">
                        <i class="fas fa-save me-1"></i> Thêm chức vụ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
