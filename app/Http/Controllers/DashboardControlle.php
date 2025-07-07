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
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalCustomers = Account::where('role_id', 3)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $lowStockCount = ProductVariant::where('quantity', '<', 5)->count();
        $recentOrders = Order::with(['account', 'orderStatus'])->latest()->take(4)->get();
        $newCustomers = Account::latest()->take(4)->get();
        $barChartData = $this->getBarChartData();
        $lineChartData = $this->getLineChartData(); // nếu có

        return view('admin.dashboard.index', compact(
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'lowStockCount',
            'recentOrders',
            'newCustomers',
            'barChartData',
            'lineChartData'
        ));
    }

    private function getBarChartData()
    {
        $orders = Order::selectRaw('MONTH(order_date) as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $labels = ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $orders[$i] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Doanh thu theo tháng',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => $data
                ]
            ]
        ];
    }


    private function getLineChartData()
    {
        return $this->getBarChartData();
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
