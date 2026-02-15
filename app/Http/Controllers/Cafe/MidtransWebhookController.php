<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Services\Midtrans\MidtransClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(private MidtransClient $midtrans) {}

    /**
     * Handle Midtrans HTTP notification (webhook).
     * POST /midtrans/notify
     */
    public function handle(Request $req)
    {
        $data = $req->all();

        $orderId      = $data['order_id'] ?? null;
        $statusCode   = (string) ($data['status_code'] ?? '');
        $grossAmount  = (string) ($data['gross_amount'] ?? '');
        $signatureKey = $data['signature_key'] ?? '';
        $txStatus     = strtolower($data['transaction_status'] ?? '');
        $fraudStatus  = strtolower($data['fraud_status'] ?? 'accept');
        $txId         = $data['transaction_id'] ?? null;

        if (!$orderId) {
            Log::warning('Midtrans notify: missing order_id');
            return response()->json(['status' => 'error', 'message' => 'missing order_id'], 400);
        }

        Log::info('Midtrans notification received', [
            'order_id'           => $orderId,
            'transaction_status' => $txStatus,
            'status_code'        => $statusCode,
        ]);

        // Verify signature
        if (!$this->midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans notify: invalid signature', ['order_id' => $orderId]);
            return response()->json(['status' => 'error', 'message' => 'invalid signature'], 403);
        }

        // Find order
        $order = Order::where('order_code', $orderId)->first();
        if (!$order) {
            Log::warning('Midtrans notify: order not found', ['order_id' => $orderId]);
            return response()->json(['status' => 'error', 'message' => 'order not found'], 404);
        }

        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            ['gateway' => 'midtrans', 'status' => 'PENDING']
        );

        // Idempotency: check for existing event with same tx status
        $existing = PaymentEvent::where('payment_id', $payment->id)
            ->where('event_type', $txStatus)
            ->exists();

        if ($existing) {
            Log::info('Midtrans notify: duplicate event, skipping', ['order_id' => $orderId, 'status' => $txStatus]);
            return response()->json(['status' => 'ok']);
        }

        // Log the event
        PaymentEvent::create([
            'payment_id' => $payment->id,
            'event_type' => $txStatus,
            'payload'    => $data,
            'is_valid'   => true,
        ]);

        // Map transaction status
        if (in_array($txStatus, ['capture', 'settlement'])) {
            // For capture, only accept if fraud_status is 'accept'
            if ($txStatus === 'capture' && $fraudStatus !== 'accept') {
                Log::warning('Midtrans notify: capture with fraud challenge', ['order_id' => $orderId]);
                return response()->json(['status' => 'ok']);
            }

            $order->update([
                'payment_status' => 'PAID',
                'order_status'   => $order->order_status === 'DITERIMA' ? 'DIPROSES' : $order->order_status,
            ]);
            $payment->update([
                'status'         => 'PAID',
                'gateway_trx_id' => $txId,
                'raw_response'   => $data,
            ]);

        } elseif ($txStatus === 'pending') {
            $order->update(['payment_status' => 'PENDING']);
            $payment->update(['status' => 'PENDING', 'gateway_trx_id' => $txId]);

        } elseif (in_array($txStatus, ['deny', 'cancel'])) {
            $order->update(['payment_status' => 'FAILED']);
            $payment->update(['status' => 'FAILED', 'gateway_trx_id' => $txId]);

        } elseif ($txStatus === 'expire') {
            $order->update(['payment_status' => 'EXPIRED']);
            $payment->update(['status' => 'EXPIRED', 'gateway_trx_id' => $txId]);
        }

        return response()->json(['status' => 'ok']);
    }
}
