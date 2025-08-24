<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Order;
use App\Models\PaymentStatus;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardControlle extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'daily');
        $paymentStatusId = $request->get('payment_status_id');
        $startDate = null;
        $endDate = null;

        switch ($range) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::tomorrow();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek()->addDay();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth()->addDay();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear()->addDay();
                break;
            case 'custom':
                $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
                $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->addDay() : null;
                break;
        }

        $ordersQuery = Order::query();
        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('order_date', [$startDate, $endDate]);
        }
        if ($paymentStatusId) {
            $ordersQuery->where('payment_status_id', $paymentStatusId);
        }

        $orders = $ordersQuery->get();

        // Tổng doanh thu
        $totalRevenueAll = $orders->sum('total_amount');
        $totalRevenueDelivered = $orders->where('order_status_id', 5)->sum('total_amount');
        $totalRevenueToday = Order::whereDate('order_date', Carbon::today())->sum('total_amount');

        // Biểu đồ doanh thu
        $barChartData = $orders->where('order_status_id', 5)
            ->groupBy(function ($order) use ($range) {
                switch ($range) {
                    case 'yearly':
                        return Carbon::parse($order->order_date)->format('Y-m');
                    case 'monthly':
                        return Carbon::parse($order->order_date)->format('d/m');
                    case 'weekly':
                    case 'daily':
                    default:
                        return Carbon::parse($order->order_date)->format('d/m');
                }
            })->map(function ($group) {
                return $group->sum('total_amount');
            });
            $barChartData = $this->generateChartData($range, $paymentStatusId, $startDate, $endDate);
        // Lấy danh sách đơn hàng theo bộ lọc
        $filteredOrders = $orders->where('order_status_id', 5)->sortByDesc('order_date');


        // Thống kê khác
        $totalCustomers = Account::count();
        $totalProducts = Product::count();
        $totalOrders = Order::where('order_status_id',1)->count();
        // Đếm số biến thể gần hết hàng
$lowStockVariantsCount = ProductVariant::where('quantity', '<', 5)->count();

// Lấy chi tiết 1 biến thể gần hết hàng (ví dụ số lượng nhỏ nhất)
$lowStockVariant = ProductVariant::where('quantity', '<', 5)
    ->with('product') // load luôn product cha
    ->orderBy('quantity', 'asc')
    ->first();
        $recentOrders = Order::orderByDesc('order_date')->take(5)->get();
        $newCustomers = Account::orderByDesc('created_at')->take(5)->get();

        $paymentStatuses = PaymentStatus::all();

        // Trả dữ liệu về view
        return view('admin.dashboard.index', compact(
            'totalRevenueAll',
            'totalRevenueDelivered',
            'totalRevenueToday',
            'barChartData',
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'lowStockVariantsCount',
            'lowStockVariant',
            'recentOrders',
            'newCustomers',
            'paymentStatuses',
            'filteredOrders',
            'range'
        ));
    }

    protected function generateChartData($range, $paymentStatusId = null, $startDate = null, $endDate = null, $deliveredStatusId = null)
{
    $query = Order::query();

    // Nếu cần lọc theo trạng thái giao hàng
    if (!is_null($deliveredStatusId)) {
        $query->where('order_status_id', $deliveredStatusId);
    }

    // Nếu cần lọc theo trạng thái thanh toán
    if (!is_null($paymentStatusId)) {
        $query->where('payment_status_id', $paymentStatusId);
    }

    // Thiết lập khoảng thời gian và định dạng group
    $groupFormat = '%Y-%m-%d'; // mặc định theo ngày
    switch ($range) {
        case '7days':
            $start = now()->subDays(6)->startOfDay();
            $end = now()->endOfDay();
            $query->whereBetween('order_date', [$start, $end]);
            break;

        case 'monthly':
            $now = now();
            $query->whereMonth('order_date', $now->month)
                  ->whereYear('order_date', $now->year);
            break;

        case 'yearly':
            $now = now();
            $query->whereYear('order_date', $now->year);
            $groupFormat = '%Y-%m'; // nhóm theo tháng
            break;

        case 'custom':
            if ($startDate && $endDate) {
                $query->whereBetween('order_date', [$startDate, $endDate]);
            } else {
                return $this->emptyChartData();
            }
            break;

        default:
            // Nếu không hợp lệ thì mặc định là 7 ngày gần nhất
            $start = now()->subDays(6)->startOfDay();
            $end = now()->endOfDay();
            $query->whereBetween('order_date', [$start, $end]);
            break;
    }

    // Truy vấn dữ liệu nhóm theo ngày/tháng
    $data = $query->selectRaw("DATE_FORMAT(order_date, '{$groupFormat}') as label, SUM(total_amount) as total")
                 ->groupBy('label')
                 ->orderBy('label')
                 ->get();

    // Nếu không có dữ liệu thì trả về dữ liệu trống
    if ($data->isEmpty()) {
        return $this->emptyChartData();
    }

    // Trả về dữ liệu cho biểu đồ
    return [
        'labels' => $data->pluck('label'),
        'datasets' => [[
            'label' => 'Doanh thu',
            'data' => $data->pluck('total')->map(fn($value) => round($value, 2)),
        ]],
    ];
}

// Hàm phụ trả về cấu trúc biểu đồ rỗng
protected function emptyChartData()
{
    return [
        'labels' => [],
        'datasets' => [[
            'label' => 'Doanh thu',
            'data' => [],
        ]],
    ];
}
}