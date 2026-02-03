<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index()
    {
        $range = request('range', '7d'); // 7d,30d,12m
        $days = $range === '30d' ? 30 : 7;

        $daily = Order::selectRaw('DATE(created_at) as d, SUM(grand_total) as total')
            ->where('payment_status','PAID')
            ->where('created_at','>=', now()->subDays($days))
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        return view('admin.reports', [
            'range' => $range,
            'labels' => $daily->pluck('d')->all(),
            'values' => $daily->pluck('total')->map(fn($v)=>(int)$v)->all(),
        ]);
    }
}
