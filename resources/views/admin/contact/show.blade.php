@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Chi tiết liên hệ #{{ $contact->id }}</h3>
                    <a href="{{ route('admin.contact.index') }}" class="btn btn-sm btn-outline-secondary">Quay lại</a>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Tên khách hàng</dt>
                        <dd class="col-sm-9">{{ $contact->name }}</dd>

                        <dt class="col-sm-3">Số điện thoại</dt>
                        <dd class="col-sm-9">{{ $contact->phone }}</dd>

                        <dt class="col-sm-3">Nội dung</dt>
                        <dd class="col-sm-9 white-space-pre-line">{{ $contact->message }}</dd>

                        <dt class="col-sm-3">Trạng thái</dt>
                        <dd class="col-sm-9">
                            <span class="badge {{ $contact->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                {{ $contact->status === 'pending' ? 'Chờ xử lý' : 'Đã xử lý' }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Ngày gửi</dt>
                        <dd class="col-sm-9">{{ $contact->created_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
                <div class="card-footer d-flex gap-2">
                    <form action="{{ route('admin.contact.status', $contact->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-warning" type="submit">{{ $contact->status === 'pending' ? 'Đánh dấu đã xử lý' : 'Đánh dấu chờ xử lý' }}</button>
                    </form>
                    <form action="{{ route('admin.contact.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Xóa liên hệ này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection