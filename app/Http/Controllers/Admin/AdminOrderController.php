<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('table')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderByDesc('created_at');
        
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

    /**
     * Render receipt page for admin print.
     */
    public function receipt(Order $order)
    {
        $order->load(['items.mods', 'table', 'payment.events']);

        return view('receipt', [
            'order'         => $order,
            'paymentMethod' => $order->payment?->payment_method_label ?? 'Midtrans',
            'cafeName'      => Setting::getValue('cafe_name', 'Nindito'),
            'cafeTagline'   => Setting::getValue('cafe_tagline', 'Coffee & Friends'),
            'cafeAddress'   => Setting::getValue('cafe_address'),
            'cafeWhatsapp'  => Setting::getValue('cafe_whatsapp'),
            'backUrl'       => route('admin.orders.show', $order->id),
            // Receipt customization
            'receiptLogoUrl'    => ($logo = Setting::getValue('receipt_logo')) ? asset($logo) : null,
            'receiptShowLogo'   => Setting::getValue('receipt_show_logo', '0'),
            'receiptLocation'   => Setting::getValue('receipt_cafe_location'),
            'receiptFooterText' => Setting::getValue('receipt_footer_text', 'Terima kasih! 🙏'),
            'receiptTheme'      => Setting::getValue('receipt_theme', 'normal'),
            'receiptShowStatus' => Setting::getValue('receipt_show_status_badges', '1'),
            'receiptShowCustomer' => Setting::getValue('receipt_show_customer_info', '1'),
            'receiptShowPayment'  => Setting::getValue('receipt_show_payment_method', '1'),
        ]);
    }

    public function setStatus(Request $req, Order $order)
    {
        $data = $req->validate([
            'status' => ['required', 'in:DITERIMA,DIPROSES,READY,SELESAI,DIBATALKAN'],
        ]);

        $order->update(['order_status' => $data['status']]);
        return back()->with('success', 'Status diperbarui.');
    }

    /**
     * Delete a pending/unpaid order (admin only)
     */
    public function destroy(Order $order)
    {
        // Only allow deleting unpaid orders
        if ($order->payment_status === 'PAID') {
            return back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dihapus.');
        }

        // Delete related records first
        $order->items()->delete();
        $order->payment()->delete();
        $order->feedback()->delete();
        $order->delete();

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}

