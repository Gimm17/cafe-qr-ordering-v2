<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IpaymuWebhookController extends Controller
{
    /**
     * iPaymu callback (notifyUrl) biasanya mengirim:
     * trx_id, status (pending/berhasil/expired), status_code (0/1/-2), sid, reference_id, ...
     * Pastikan idempotent (callback bisa diulang).
     */
    public function handle(Request $req)
    {
        $payload = $req->all();
        $reference = $payload['reference_id'] ?? $payload['referenceId'] ?? $payload['reference'] ?? null;
        $statusCode = (string)($payload['status_code'] ?? $payload['statusCode'] ?? '');
        $status = strtolower((string)($payload['status'] ?? ''));

        if (!$reference) {
            Log::warning('iPaymu notify without reference', $payload);
            return response('missing reference', 400);
        }

        $order = Order::where('order_code', $reference)->first();
        if (!$order) {
            Log::warning('iPaymu notify unknown order', ['ref' => $reference] + $payload);
            return response('unknown order', 404);
        }

        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            ['gateway' => 'ipaymu', 'status' => 'CREATED']
        );

        PaymentEvent::create([
            'payment_id' => $payment->id,
            'event_type' => 'notify',
            'payload' => $payload,
            'is_valid' => true, // TODO: validate signature if provided by iPaymu for notify payload
        ]);

        // Idempotency: if already PAID, do nothing
        if ($order->payment_status === 'PAID') {
            return response('ok', 200);
        }

        // Map statuses
        // status_code: 1 berhasil, 0 pending, -2 expired
        if ($statusCode === '1' || $status === 'berhasil' || $status === 'success') {
            $order->update([
                'payment_status' => 'PAID',
                'order_status' => 'DIPROSES', // Auto-process saat bayar berhasil
            ]);
            $payment->update([
                'status' => 'PAID',
                'gateway_trx_id' => $payload['trx_id'] ?? null,
            ]);
            return response('ok', 200);
        }

        if ($statusCode === '0' || $status === 'pending') {
            $order->update(['payment_status' => 'PENDING']);
            $payment->update(['status' => 'PENDING']);
            return response('ok', 200);
        }

        if ($statusCode === '-2' || $status === 'expired') {
            $order->update(['payment_status' => 'EXPIRED']);
            $payment->update(['status' => 'EXPIRED']);
            return response('ok', 200);
        }

        // fallback
        $order->update(['payment_status' => 'FAILED']);
        $payment->update(['status' => 'FAILED']);

        return response('ok', 200);
    }
}
