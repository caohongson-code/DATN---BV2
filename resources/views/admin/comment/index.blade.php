@extends('admin.layouts.app')

@section('content')
<h3>Quản lý bình luận</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Người bình luận</th>
            <th>Sản phẩm</th>
            <th>Nội dung</th>
            <th>Ngày</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($comments as $comment)
        <tr>
            <td>{{ $comment->account->full_name ?? 'Ẩn danh' }}</td>
            <td>{{ $comment->product->product_name ?? '' }}</td>
            <td>{{ $comment->comment }}</td>
            <td>{{ $comment->created_at->format('H:i d/m/Y') }}</td>
            <td>
                @if($comment->status)
                    <span class="badge bg-success">Hiển thị</span>
                @else
                    <span class="badge bg-secondary">Đang ẩn</span>
                @endif
            </td>
            <td>
               @if ($comment->status == 0)
    <a href="{{ route('comments.showComment', $comment->id) }}" class="btn btn-success btn-sm">Hiện</a>
@else
    <a href="{{ route('comments.hide', $comment->id) }}" class="btn btn-warning btn-sm">Ẩn</a>
@endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $comments->links() }}
@endsection
