<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Midtrans\MidtransClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private MidtransClient $midtrans) {}

    /**
     * Show the custom in-app payment page with Midtrans Snap.
     */
    public function pay(Order $order)
    {
        if ($order->payment_status === 'PAID') {
            return redirect()->route('cafe.order.show', ['order' => $order->order_code])
                ->with('success', 'Pesanan sudah dibayar.');
        }

        // Check if order is expired (older than 10 minutes)
        if ($order->created_at->diffInMinutes(now()) >= 10) {
            $order->update(['payment_status' => 'EXPIRED']);
            return redirect()->route('cafe.order.show', ['order' => $order->order_code])
                ->with('error', 'Pesanan telah kadaluarsa. Silakan pesan ulang.');
        }

        // Create or get payment record
        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            ['gateway' => 'midtrans', 'status' => 'CREATED']
        );

        // Reuse existing Snap token if already created
        $snapToken = $payment->gateway_session_id;

        if (!$snapToken) {
            $snap = $this->midtrans->createSnapToken($order);

            if (!$snap['token']) {
                Log::warning('Midtrans snap token failed', ['order' => $order->order_code, 'error' => $snap['error'] ?? 'unknown']);
                return redirect()->route('cafe.order.show', ['order' => $order->order_code])
                    ->with('error', 'Gagal membuat sesi pembayaran: ' . ($snap['error'] ?? 'Silakan coba lagi.'));
            }

            $snapToken = $snap['token'];
            $payment->update([
                'status'             => 'PENDING',
                'gateway_session_id' => $snapToken,
                'gateway_url'        => $snap['redirect_url'] ?? null,
            ]);
            $order->update(['payment_status' => 'PENDING']);
        }

        $items = $order->items()->get();

        return view('cafe.pay', [
            'order'     => $order,
            'items'     => $items,
            'snapToken' => $snapToken,
            'clientKey' => config('midtrans.client_key'),
            'snapUrl'   => config('midtrans.snap_url'),
        ]);
    }

    /**
     * AJAX endpoint: get/refresh Snap token for an order.
     */
    public function getSnapToken(Order $order)
    {
        if ($order->payment_status === 'PAID') {
            return response()->json(['error' => 'Pesanan sudah dibayar'], 400);
        }

        $snap = $this->midtrans->createSnapToken($order);

        if (!$snap['token']) {
            return response()->json(['error' => $snap['error'] ?? 'Gagal membuat token'], 500);
        }

        return response()->json(['token' => $snap['token']]);
    }
}
