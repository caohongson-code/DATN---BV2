@extends('client.user.dashboard')

@section('dashboard-content')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    .wallet-balance-box {
        background-color: #fff3e0;
        border: 1px solid #ffe0b2;
        padding: 20px;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .wallet-balance-box h5 {
        font-weight: bold;
        color: #ff5722;
    }

    .wallet-actions {
        display: flex;
        justify-content: space-around;
        margin-bottom: 30px;
    }

    .wallet-icon-action {
        text-align: center;
        text-decoration: none;
        color: #333;
    }

    .wallet-icon-action img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        transition: transform 0.2s;
    }

    .wallet-icon-action:hover img {
        transform: scale(1.1);
    }

    .nav-pills .nav-link.active {
        background-color: #ff5722;
        color: #fff;
    }

    .transaction-table {
        background-color: #fff;
        border-radius: 10px;
        padding: 10px;
    }

    .transaction-table th {
        background-color: #fff8e1;
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush

<div class="container py-4">
    <div class="wallet-balance-box">
        <h5>Số dư hiện tại:</h5>
        <h3 class="text-success fw-bold">{{ number_format($wallet->balance, 0, ',', '.') }}đ</h3>
    </div>

   <div class="wallet-actions">
    <a href="#" class="wallet-icon-action">
        <div><i class="fa-solid fa-money-bill-wave fa-2x text-success"></i></div>
        <div class="mt-1">Nạp tiền</div>
    </a>
    <a href="#" class="wallet-icon-action">
        <div><i class="fa-solid fa-hand-holding-dollar fa-2x text-danger"></i></div>
        <div class="mt-1">Rút tiền</div>
    </a>
    <a href="#all" data-bs-toggle="pill" class="wallet-icon-action">
        <div><i class="fa-solid fa-clock-rotate-left fa-2x text-primary"></i></div>
        <div class="mt-1">Lịch sử</div>
    </a>
</div>


    <!-- Tabs -->
    <ul class="nav nav-pills mb-3 justify-content-center" id="walletTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">Thông báo biến động</button>
        </li>
    
    </ul>

    <!-- Nội dung tab -->
    <div class="tab-content" id="walletTabContent">
        <!-- Tất cả -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="transaction-table">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Loại</th>
                            <th>Số tiền</th>
                            <th>Thời gian</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallet->transactions as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($item->type === 'deposit')
                                        <span class="text-success">Nạp tiền</span>
                                    @elseif($item->type === 'withdraw')
                                        <span class="text-danger">Rút tiền</span>
                                    @else
                                        {{ ucfirst($item->type) }}
                                    @endif
                                </td>
                                <td>{{ number_format($item->amount, 0, ',', '.') }}đ</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->note }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Không có giao dịch nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

      

     
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
