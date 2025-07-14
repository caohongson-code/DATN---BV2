@extends('admin.layouts.app')

@section('title', 'Yêu cầu trả hàng')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0"><i class="fas fa-undo-alt me-2 text-danger"></i> Yêu cầu trả hàng</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Lý do</th>
                            <th>Ảnh</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>#{{ $request->order_id }}</td>
                                <td class="text-start">
                                    <strong>{{ $request->order->account->full_name ?? 'Không rõ' }}</strong><br>
                                    <small class="text-muted">{{ $request->order->account->email ?? '' }}</small>
                                </td>
                                <td>{{ $request->reason }}</td>
                                <td>
                                    @foreach(json_decode($request->images, true) ?? [] as $img)
                                        <img src="{{ asset('storage/' . $img) }}" width="60">
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge
                                        @if($request->status == 'pending') bg-warning
                                        @elseif($request->status == 'approved') bg-success
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($request->status == 'pending')
                                        <a href="{{ route('admin.return_requests.approve', $request->id) }}"
                                           class="btn btn-sm btn-success"
                                           onclick="return confirm('Duyệt yêu cầu này?')">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="{{ route('admin.return_requests.reject', $request->id) }}"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Từ chối yêu cầu này?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @else
                                        <em class="text-muted">Không khả dụng</em>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted text-center">Chưa có yêu cầu trả hàng.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
