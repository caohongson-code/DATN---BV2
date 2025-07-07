@extends('admin.layouts.app')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
     
        <div class="col-md-3">
            <a href="{{ url('admin/customers') }}" class="text-decoration-none">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Tổng khách hàng</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5>{{ $totalCustomers }} khách hàng</h5>
                        <span class="text-white small">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/products') }}" class="text-decoration-none">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">Tổng sản phẩm</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5>{{ $totalProducts }} sản phẩm</h5>
                        <span class="text-white small">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/orders') }}" class="text-decoration-none">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">Tổng đơn hàng</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5>{{ $totalOrders }} đơn hàng</h5>
                        <span class="text-white small">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/products?low_stock=1') }}" class="text-decoration-none">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Sắp hết hàng</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5>{{ $lowStockCount }} sản phẩm</h5>
                        <span class="text-white small">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Tình trạng đơn hàng --}}
    <div class="card mb-4" >
        <div class="card-header">
            <i class="fas fa-table me-1" ></i>
            Tình trạng đơn hàng gần đây
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khách</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->account->full_name ?? 'Không rõ' }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                            <td>
                                <span class="badge bg-{{ $order->orderStatus->status_color ?? 'secondary' }}">
                                    {{ $order->orderStatus->status_name ?? 'Không rõ' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Khách hàng mới --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Khách hàng mới
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Ngày sinh</th>
                        <th>Điện thoại</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($newCustomers as $cus)
                        <tr>
                            <td>#{{ $cus->id }}</td>
                            <td>{{ $cus->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($cus->dob)->format('d/m/Y') }}</td>
                            <td>{{ $cus->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <canvas id="lineChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {!! json_encode($lineChartData) !!}
    });

    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {!! json_encode($barChartData) !!}
    });
</script>
@endsection
