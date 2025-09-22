@extends('client.layouts.app')

@section('title', 'Thông báo')

@section('content')
    <div class="container-fluid px-4">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white border-bottom">
                <h4 class="fw-bold mb-0"><i class="fas fa-bell me-2"></i> Thông báo của bạn</h4>
            </div>
            <div class="card-body">
                @if ($notifications->isEmpty())
                    <p class="text-muted">Bạn chưa có thông báo nào.</p>
                @else
                    <ul class="list-group">
                        @foreach ($notifications as $notification)
                            <li class="list-group-item {{ $notification->read ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-1">{{ $notification->message }}</p>
                                        <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    @if (!$notification->read)
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-primary">Đánh dấu đã đọc</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{ $notifications->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection