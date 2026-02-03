<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('table')->orderByDesc('created_at');
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('order_status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment') && $request->payment) {
            $query->where('payment_status', $request->payment);
        }
        
        // Filter by fulfillment type
        if ($request->has('fulfillment') && $request->fulfillment) {
            $query->where('fulfillment_type', $request->fulfillment);
        }

        $orders = $query->get();

        return view('admin.orders', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items.mods', 'table', 'payment', 'feedback']);
        return view('admin.order_show', ['order' => $order]);
    }

    public function setStatus(Request $req, Order $order)
    {
        $data = $req->validate([
            'status' => ['required', 'in:DITERIMA,DIPROSES,READY,SELESAI,DIBATALKAN'],
        ]);

        $order->update(['order_status' => $data['status']]);
        return back()->with('success', 'Status diperbarui.');
    }
}
