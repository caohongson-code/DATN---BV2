@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Quản lý liên hệ</h3>
                    <a href="{{ route('admin.contact.index') }}" class="btn btn-sm btn-outline-secondary">Tải lại</a>
                </div>
                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày gửi</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td>{{ $contact->id }}</td>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->phone }}</td>
                                        <td class="text-break" style="max-width: 360px; white-space: pre-line;">{{ $contact->message }}</td>
                                        <td>
                                            <span class="badge {{ $contact->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                {{ $contact->status === 'pending' ? 'Chờ xử lý' : 'Đã xử lý' }}
                                            </span>
                                        </td>
                                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="d-flex gap-2">
                                            <a href="{{ route('admin.contact.show', $contact->id) }}" class="btn btn-sm btn-primary">Xem</a>
                                            <form action="{{ route('admin.contact.status', $contact->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
<button class="btn btn-sm btn-warning" type="submit">{{ $contact->status === 'pending' ? 'Đánh dấu đã xử lý' : 'Đánh dấu chờ xử lý' }}</button>
                                            </form>
                                            <form action="{{ route('admin.contact.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa liên hệ này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Chưa có dữ liệu liên hệ.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
