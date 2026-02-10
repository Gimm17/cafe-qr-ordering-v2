<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        // F-05: Ownership check — order harus milik table session saat ini
        if ($order->table_id !== session('cafe_table_id')) {
            abort(403, 'Akses ditolak: pesanan bukan milik meja Anda.');
        }

        $order->load('items','feedback','table');
        return view('cafe.order', [
            'order' => $order,
            'tableNo' => session('cafe_table_no'),
        ]);
    }

    public function statusJson(Order $order)
    {
        // F-05: Ownership check
        if ($order->table_id !== session('cafe_table_id')) {
            abort(403, 'Akses ditolak.');
        }

        // Generate ETag based on order's last update and status
        $etag = 'W/"' . $order->updated_at->timestamp . '-' . $order->order_status . '-' . $order->payment_status . '"';
        
        // Check if client's cached version is still valid
        $clientEtag = request()->headers->get('If-None-Match');
        if ($clientEtag === $etag) {
            return response('', 304)->setEtag($etag, true);
        }

        return response()->json([
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'updated_at' => $order->updated_at?->toISOString(),
        ])->setEtag($etag, true)
           ->header('Cache-Control', 'private, no-cache');
    }

    /**
     * Show order history for current table session
     */
    public function history()
    {
        $tableId = session('cafe_table_id');
        
        if (!$tableId) {
            return redirect()->route('cafe.menu')->with('error', 'Silakan scan QR meja terlebih dahulu.');
        }

        // Get all orders for this table in the last 24 hours
        $orders = Order::where('table_id', $tableId)
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->with(['items', 'table'])
            ->get();

        return view('cafe.history', [
            'orders' => $orders,
            'tableNo' => session('cafe_table_no'),
        ]);
    }

    /**
     * Cancel an unpaid order
     */
    public function cancel(Order $order)
    {
        $tableId = session('cafe_table_id');

        // Verify order belongs to this table
        if ($order->table_id !== $tableId) {
            return back()->with('error', 'Anda tidak dapat membatalkan pesanan ini.');
        }

        // Only allow cancel for unpaid orders
        if ($order->payment_status === 'PAID') {
            return back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
        }

        // Delete the order
        $order->items()->delete();
        $order->payment()->delete();
        $order->delete();

        return redirect()->route('cafe.history')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
