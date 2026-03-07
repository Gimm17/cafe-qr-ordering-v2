<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Midtrans\MidtransClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(private MidtransClient $midtrans) {}

    /**
     * Sync payment status from Midtrans API.
     * Idempotent — skip if already PAID.
     * Uses DB transaction + lockForUpdate() to prevent race conditions.
     */
    public function syncPaymentStatus(Order $order): string
    {
        // Fast path: already paid
        if ($order->payment_status === 'PAID') return 'PAID';

        $statusData = $this->midtrans->getStatus($order->order_code);

        if (!$statusData) {
            Log::warning('PaymentService: Cannot reach Midtrans', ['order' => $order->order_code]);
            return 'unknown';
        }

        $txStatus    = strtolower($statusData['transaction_status'] ?? '');
        $fraudStatus = strtolower($statusData['fraud_status'] ?? 'accept');
        $txId        = $statusData['transaction_id'] ?? null;

        return DB::transaction(function () use ($order, $txStatus, $fraudStatus, $txId, $statusData) {
            // Reload with lock to prevent race conditions
            $order = Order::lockForUpdate()->find($order->id);

            if ($order->payment_status === 'PAID') return 'PAID'; // Double-check inside lock

            $payment = Payment::firstOrCreate(
                ['order_id' => $order->id],
                ['gateway' => 'midtrans', 'status' => 'PENDING']
            );

            if (in_array($txStatus, ['capture', 'settlement'])) {
                if ($txStatus === 'capture' && $fraudStatus !== 'accept') {
                    return 'challenge';
                }

                $order->update([
                    'payment_status' => 'PAID',
                    'order_status'   => $order->order_status === 'DITERIMA' ? 'DIPROSES' : $order->order_status,
                ]);
                $payment->update([
                    'status'         => 'PAID',
                    'gateway_trx_id' => $txId,
                    'raw_response'   => $statusData,
                ]);

                Log::info('PaymentService: PAID', ['order' => $order->order_code]);
                return 'PAID';
            }

            if ($txStatus === 'pending') {
                $order->update(['payment_status' => 'PENDING']);
                $payment->update(['status' => 'PENDING', 'gateway_trx_id' => $txId]);
                return 'PENDING';
            }

            if (in_array($txStatus, ['deny', 'cancel'])) {
                $order->update(['payment_status' => 'FAILED']);
                $payment->update(['status' => 'FAILED', 'gateway_trx_id' => $txId]);
                return 'FAILED';
            }

            if ($txStatus === 'expire') {
                $order->update(['payment_status' => 'EXPIRED']);
                $payment->update(['status' => 'EXPIRED', 'gateway_trx_id' => $txId]);
                return 'EXPIRED';
            }

            return $txStatus;
        });
    }
}
