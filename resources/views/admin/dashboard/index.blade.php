@extends('admin.layouts.app')

@section('title', 'Trang quản trị')

@section('content')

<style>
    .card-summary {
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        background: linear-gradient(135deg, #1dd1a1, #10ac84);
        color: white;
        padding: 24px;
        margin-bottom: 24px;
    }
    .card-summary.blue {
        background: linear-gradient(135deg, #48dbfb, #1e90ff);
    }
    .card-summary .icon {
        font-size: 36px;
    }
    .table-section {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .table thead {
        background-color: #f8f9fa;
    }
</style>


<div class="container-fluid py-4">
    <div class="dashboard-title">📊 Thống kê tổng quan</div>
    {{-- THỐNG KÊ NHANH --}}
    <div class="row">
        <div class="col-md-3">
            <a href="{{ url('admin/customers') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                    <div class="card-body fw-bold">Tổng khách hàng</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $totalCustomers }} khách hàng</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/products') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #36b9cc, #1c768f);">
                    <div class="card-body fw-bold">Tổng sản phẩm</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $totalProducts }} sản phẩm</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ url('admin/orders') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #f6c23e, #dda20a);">
                    <div class="card-body fw-bold">Đơn hàng chưa xác nhận gần đây</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $totalOrders }} đơn hàng</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
<a href="{{ url('admin/products?low_stock=1') }}" class="text-decoration-none">
                <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #e74a3b, #b92c23);">
                    <div class="card-body fw-bold">Sắp hết hàng</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $lowStockProducts }} sản phẩm</h5>
                        <span class="btn btn-outline-light btn-sm">Xem →</span>
                    </div>
                </div>
            </a>
        </div>
    </div>


    <div class="row align-items-center mb-3">
        <div class="col-auto">
            <h4 class="fw-bold mb-0">📈 Biểu đồ doanh thu</h4>
        </div>
    </div>


        <form method="GET" class="row mb-4 g-3 dashboard-filters">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Thời gian :</label>
                <select name="range" class="form-select">
                    <option value="">Vui lòng chọn</option>
                    <option value="daily" {{ $range == 'daily' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="weekly" {{ $range == 'weekly' ? 'selected' : '' }}>Tuần này</option>
                    <option value="monthly" {{ $range == 'monthly' ? 'selected' : '' }}>Tháng này</option>
                    <option value="yearly" {{ $range == 'yearly' ? 'selected' : '' }}>Năm nay</option>
                    <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Tùy chỉnh</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="start_date" class="form-label">Ngày bắt đầu :</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-3">
                <label for="end_date" class="form-label">Ngày kết thúc :</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        <div class="col-md-3">
            <button class="btn btn-outline-primary w-100">Lọc</button>
        </div>
    </form>

    <div class="card shadow-sm mb-5" style="min-height: 400px;">
        <div class="card-body position-relative" style="height: 100%;">
            <canvas id="revenueChart" style="height: 100% !important;"></canvas>
        </div>
    </div>
    <div class="row mb-4">
        <!-- Tổng doanh thu tất cả đơn -->
        <div class="col-md-6">
            <div class="card text-white bg-gradient-primary shadow h-100 py-2" style="background: linear-gradient(90deg, #36b9cc, #1cc88a); border-radius: 1rem;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
<h5 class="font-weight-bold">💰 Tổng doanh thu (Tất cả đơn)</h5>
                        <h3>{{ number_format($totalRevenueAll, 0, ',', '.') }}₫</h3>
                    </div>
                    <div>
                        <i class="fas fa-coins fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng doanh thu đã giao hàng -->
        <div class="col-md-6">
            <div class="card text-white bg-gradient-success shadow h-100 py-2" style="background: linear-gradient(90deg, #4e73df, #1cc88a); border-radius: 1rem;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold">📦 Doanh thu đã giao hàng</h5>
                        <h3>{{ number_format($totalRevenueDelivered, 0, ',', '.') }}₫</h3>
                    </div>
                    <div>
                        <i class="fas fa-shipping-fast fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        {{-- Đơn hàng mới --}}
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-bold">🛒 Đơn hàng mới</div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên khách</th>
                                <th class="text-end">Tổng tiền</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr onclick="window.location.href='{{ route('admin.orders.show', $order->id) }}'" style="cursor: pointer;">
                                    <td>{{ $order->account->full_name ?? 'Không có' }}</td>
                                    <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $order->orderStatus->status_name ?? 'Không xác định' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Không có đơn hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Khách hàng mới --}}
<div class="col-lg-6 col-md-12">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-bold">🧍‍♂️ Khách hàng mới</div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Ngày sinh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($newCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->full_name ?? '-' }}</td>
                                    <td>{{ $customer->email ?? '-' }}</td>
                                    <td>{{ $customer->phone ?? '-' }}</td>
                                    <td>{{ optional($customer->birth_date)->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Không có khách hàng mới.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <hr class="my-4">

    <div class="card shadow-sm">
        <div class="card-header fw-bold bg-light d-flex align-items-center">
            📃 <span class="ms-2">Chi tiết đơn hàng</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">📅 Ngày</th>
                        <th>👤 Khách hàng</th>
                        <th class="text-center">📌 Trạng thái</th>
                        <th class="text-end">💰 Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($filteredOrders as $order)
                        <tr class="clickable-row" onclick="window.location.href='{{ route('admin.orders.show', $order->id) }}'">
                            <td class="text-center">{{ $order->order_date->format('d/m/Y') }}</td>
                            <td>{{ $order->account->full_name ?? 'Không có' }}</td>
                            <td class="text-center">
                                @php
                                    $statusColor = match($order->orderStatus->status_name ?? '') {
                                        'Đang xử lý' => 'warning',
                                        'Đã giao' => 'success',
'Đã huỷ' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ $order->orderStatus->status_name ?? 'Không xác định' }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Không có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData = @json($barChartData);
        const ctx = document.getElementById('revenueChart').getContext('2d');

        if (window.revenueChartInstance) {
            window.revenueChartInstance.destroy(); // tránh vẽ lại nhiều lần nếu reload AJAX
        }

        window.revenueChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Doanh thu',
                    data: chartData.datasets[0].data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return value.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection