<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $order->load('items','feedback','table');
        return view('cafe.order', [
            'order' => $order,
            'tableNo' => session('cafe_table_no'),
        ]);
    }

    public function statusJson(Order $order)
    {
        return response()->json([
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'updated_at' => $order->updated_at?->toISOString(),
        ]);
    }
}
