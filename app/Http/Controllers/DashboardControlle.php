<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class DashboardControlle extends Controller
{
    /**
     * Hiển thị trang dashboard admin.
     */
    public function index(Request $request)
    {
        $range = $request->query('range', 'monthly'); // Lọc theo ngày/tháng/năm

        $totalCustomers = Account::where('role_id', 3)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::where('order_status_id', 1)->count();
        $lowStockCount = ProductVariant::where('quantity', '<', 5)->count();

        $recentOrders = Order::with(['account', 'orderStatus'])
        ->where('order_status_id',1)
            ->latest()
            ->take(4)
            ->get();

        $newCustomers = Account::latest()->take(4)->get();

        $barChartData = $this->getChartData($range);
        $lineChartData = $barChartData; // Dùng chung để demo

        return view('admin.dashboard.index', compact(
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'lowStockCount',
            'recentOrders',
            'newCustomers',
            'barChartData',
            'lineChartData',
            'range'
        ));
    }

    /**
     * Tạo dữ liệu biểu đồ theo dạng lọc (ngày / tháng / năm).
     */
    private function getChartData($range)
    {
        $query = Order::query();

        switch ($range) {
            case 'daily':
                $query = $query->selectRaw('DATE(order_date) as label, SUM(total_amount) as revenue')
                               ->groupByRaw('DATE(order_date)')
                               ->orderBy('label');
                break;

            case 'yearly':
                $query = $query->selectRaw('YEAR(order_date) as label, SUM(total_amount) as revenue')
                               ->groupByRaw('YEAR(order_date)')
                               ->orderBy('label');
                break;

            case 'monthly':
            default:
                $query = $query->selectRaw('MONTH(order_date) as label, SUM(total_amount) as revenue')
                               ->groupByRaw('MONTH(order_date)')
                               ->orderBy('label');
                break;
        }

        $results = $query->pluck('revenue', 'label')->toArray();

        if ($range === 'daily') {
            $labels = [];
            $data = [];
            $start = now()->subDays(29)->startOfDay();
            for ($date = $start->copy(); $date <= now(); $date->addDay()) {
                $label = $date->format('Y-m-d');
                $labels[] = $label;
                $data[] = $results[$label] ?? 0;
            }
        } elseif ($range === 'yearly') {
            $currentYear = now()->year;
            $labels = [];
            $data = [];
            for ($i = $currentYear - 4; $i <= $currentYear; $i++) {
                $labels[] = "Năm $i";
                $data[] = $results[$i] ?? 0;
            }
        } else {
            $labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                       'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $data[] = $results[$i] ?? 0;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Doanh thu',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => $data
                ]
            ]
        ];
    }
}