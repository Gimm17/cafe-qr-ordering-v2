<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        
        $todaySales = Order::whereDate('created_at', $today)
            ->where('payment_status', 'PAID')
            ->sum('grand_total');
            
        $todayOrders = Order::whereDate('created_at', $today)->count();
        
        $pendingOrders = Order::whereIn('order_status', ['DITERIMA', 'DIPROSES'])
            ->where('payment_status', 'PAID')
            ->count();
            
        $totalProducts = Product::where('is_active', true)->count();

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
            'todaySales' => $todaySales,
            'todayOrders' => $todayOrders,
            'pendingOrders' => $pendingOrders,
            'totalProducts' => $totalProducts,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
        ]);
    }
}
