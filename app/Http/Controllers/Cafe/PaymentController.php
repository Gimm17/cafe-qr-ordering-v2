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

        // Check if order is expired (older than 10 minutes)
        if ($order->created_at->diffInMinutes(now()) >= 10) {
            $order->update(['payment_status' => 'EXPIRED']);
            return redirect()->route('cafe.order.show', ['order' => $order->order_code])
                ->with('error', 'Pesanan telah kadaluarsa. Silakan pesan ulang.');
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
        // PENTING: description tidak boleh kosong - gunakan product name sebagai fallback
        $descriptions = $items->map(fn($item) => (string)($item->note ?: $item->product_name))->values()->all();

        // Generate callback URLs using route() helper (otomatis sinkron dengan route definition)
        $returnUrl = route('ipaymu.return', ['order_code' => $order->order_code], true);
        $cancelUrl = route('ipaymu.cancel', ['order_code' => $order->order_code], true);
        $notifyUrl = route('ipaymu.notify', [], true);

        $payload = [
            'account' => $va,  // ✅ WAJIB untuk Payment Redirect v2
            'product' => $products,
            'qty' => $qtys,
            'price' => $prices,
            'description' => $descriptions,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl,
            'notifyUrl' => $notifyUrl,
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

        // Eager-check: verify payment with iPaymu if webhook hasn't arrived yet
        $order = Order::where('order_code', $code)->first();
        if ($order && $order->payment_status !== 'PAID') {
            $payment = $order->payment;
            if ($payment && $payment->gateway_trx_id) {
                try {
                    $res = $this->ipaymu->checkTransaction($payment->gateway_trx_id);
                    $body = $res['body'] ?? [];
                    $apiStatus = (int)($body['Status'] ?? 0);
                    $txStatusCode = (int)($body['Data']['StatusCode'] ?? -1);

                    if ($apiStatus === 200 && $txStatusCode === 1) {
                        $order->update([
                            'payment_status' => 'PAID',
                            'order_status' => 'DIPROSES',
                        ]);
                        $payment->update(['status' => 'PAID']);
                        return redirect()->route('cafe.order.show', ['order' => $code])
                            ->with('success', 'Pembayaran berhasil! Pesanan sedang diproses.');
                    }
                } catch (\Throwable $e) {
                    Log::warning('Eager payment check failed', ['order' => $code, 'error' => $e->getMessage()]);
                }
            }
        }

        return redirect()->route('cafe.order.show', ['order' => $code])->with('success', 'Kembali dari pembayaran.');
    }

    public function cancel()
    {
        $code = request('order_code');
        if (!$code) return redirect()->route('cafe.menu');
        return redirect()->route('cafe.order.show', ['order' => $code])->with('error', 'Pembayaran dibatalkan.');
    }
}
