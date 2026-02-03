<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Ipaymu\IpaymuClient;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private IpaymuClient $ipaymu) {}

    public function redirect(Order $order)
    {
        if ($order->payment_status === 'PAID') {
            return redirect()->route('cafe.order.show', ['order' => $order->order_code]);
        }

        // Build payload for Redirect Payment
        $cfg = config('ipaymu');
        $va = (string) ($cfg['va'] ?? '');
        $items = $order->items()->get();
        
        // Ensure strictly typed arrays for iPaymu
        $products = $items->pluck('product_name')->map(fn($v) => (string)$v)->values()->all();
        $qtys = $items->pluck('qty')->map(fn($v) => (int)$v)->values()->all();
        // penting: jangan float, bikin encoding beda-beda
        $prices = $items->pluck('unit_price')->map(fn($v) => (int) round((float) $v))->values()->all();
        $descriptions = $items->pluck('note')->map(fn($v) => (string)($v ?? ''))->values()->all();

        $payload = [
            'account' => $va,  // ✅ WAJIB untuk Payment Redirect v2
            'product' => $products,
            'qty' => $qtys,
            'price' => $prices,
            'description' => $descriptions,
            'returnUrl' => $cfg['return_url'].'?order_code='.$order->order_code,
            'cancelUrl' => $cfg['cancel_url'].'?order_code='.$order->order_code,
            'notifyUrl' => $cfg['notify_url'],
            'referenceId' => $order->order_code,
            'buyerName' => (string)$order->customer_name,
        ];

        // create payment record
        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            ['gateway' => 'ipaymu', 'status' => 'CREATED']
        );

        $res = $this->ipaymu->createRedirectPayment($payload);
        $body = $res['body'] ?? [];
        $paymentUrl = $this->ipaymu->parseRedirectUrl($body);
        $sessionId = $this->ipaymu->parseSessionId($body);

        $payment->update([
            'status' => ($paymentUrl ? 'PENDING' : 'FAILED'),
            'gateway_session_id' => $sessionId,
            'gateway_url' => $paymentUrl,
            'raw_response' => $body,
        ]);

        $order->update(['payment_status' => ($paymentUrl ? 'PENDING' : 'FAILED')]);

        if (!$paymentUrl) {
            Log::warning('iPaymu redirect payment failed', ['order' => $order->order_code, 'resp' => $res]);
            $errorMsg = $body['Message'] ?? 'Gagal membuat sesi pembayaran. Silakan coba lagi.';
            return redirect()->route('cafe.order.show', ['order' => $order->order_code])
                ->with('error', 'iPaymu Error: ' . $errorMsg);
        }

        return redirect()->away($paymentUrl);
    }

    public function return()
    {
        $code = request('order_code');
        if (!$code) return redirect()->route('cafe.menu');
        return redirect()->route('cafe.order.show', ['order' => $code])->with('success', 'Kembali dari pembayaran.');
    }

    public function cancel()
    {
        $code = request('order_code');
        if (!$code) return redirect()->route('cafe.menu');
        return redirect()->route('cafe.order.show', ['order' => $code])->with('error', 'Pembayaran dibatalkan.');
    }
}
