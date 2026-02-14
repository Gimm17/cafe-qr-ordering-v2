<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Use date range instead of whereDate for better index usage
        $start = now()->startOfDay();
        $end = now()->endOfDay();
        
        // Cache dashboard stats for 30 seconds to reduce DB load
        $stats = Cache::remember('dashboard_stats_' . now()->format('Y-m-d-H-i'), 30, function () use ($start, $end) {
            return [
                'todaySales' => Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'PAID')
                    ->sum('grand_total'),
                'todayOrders' => Order::whereBetween('created_at', [$start, $end])->count(),
                'pendingOrders' => Order::whereIn('order_status', ['DITERIMA', 'DIPROSES'])
                    ->where('payment_status', 'PAID')
                    ->count(),
                'totalProducts' => Product::where('is_active', true)->count(),
            ];
        });

        $recentOrders = Order::with('table')
            ->latest()
            ->limit(5)
            ->get();
            
        $topProducts = Product::select('products.*')
            ->selectRaw('(SELECT COALESCE(SUM(order_items.qty), 0) FROM order_items WHERE order_items.product_id = products.id) as order_count')
            ->where('is_active', true)
            ->orderByDesc('order_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'todaySales' => $stats['todaySales'],
            'todayOrders' => $stats['todayOrders'],
            'pendingOrders' => $stats['pendingOrders'],
            'totalProducts' => $stats['totalProducts'],
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'cafeIsOpen' => Setting::isCafeOpen(),
        ]);
    }
}
