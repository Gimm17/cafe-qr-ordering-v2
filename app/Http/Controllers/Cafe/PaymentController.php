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
        $items = $order->items()->get();

        $payload = [
            'product' => $items->pluck('product_name')->values()->all(),
            'qty' => $items->pluck('qty')->values()->all(),
            'price' => $items->pluck('unit_price')->values()->all(),
            'description' => $items->pluck('note')->map(fn($v) => $v ?? '')->values()->all(),
            'returnUrl' => $cfg['return_url'].'?order_code='.$order->order_code,
            'cancelUrl' => $cfg['cancel_url'].'?order_code='.$order->order_code,
            'notifyUrl' => $cfg['notify_url'],
            'referenceId' => $order->order_code,
            'buyerName' => $order->customer_name,
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
            return redirect()->route('cafe.order.show', ['order' => $order->order_code])
                ->with('error', 'Gagal membuat sesi pembayaran. Coba lagi atau hubungi kasir.');
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
