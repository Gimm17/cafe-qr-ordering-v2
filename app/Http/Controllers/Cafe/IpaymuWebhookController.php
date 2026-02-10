<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Services\Ipaymu\IpaymuClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IpaymuWebhookController extends Controller
{
    public function __construct(private IpaymuClient $ipaymu) {}

    /**
     * iPaymu callback (notifyUrl).
     * POST: process payment notification with server-to-server verification (F-03).
     * GET:  URL validation only — returns OK without processing.
     */
    public function handle(Request $req)
    {

        $payload = $req->all();
        $reference = $payload['reference_id'] ?? $payload['referenceId'] ?? $payload['reference'] ?? null;
        $statusCode = (string)($payload['status_code'] ?? $payload['statusCode'] ?? '');
        $status = strtolower((string)($payload['status'] ?? ''));
        $trxId = (string)($payload['trx_id'] ?? $payload['trxId'] ?? '');

        if (!$reference) {
            Log::warning('iPaymu notify without reference');
            return response('missing reference', 400);
        }

        $order = Order::where('order_code', $reference)->first();
        if (!$order) {
            Log::warning('iPaymu notify unknown order', ['ref' => $reference]);
            return response('unknown order', 404);
        }

        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            ['gateway' => 'ipaymu', 'status' => 'CREATED']
        );

        // Idempotency: if this trx_id has already been processed, skip
        if ($trxId && PaymentEvent::where('payment_id', $payment->id)
                ->where('event_type', 'notify')
                ->where('is_valid', true)
                ->whereJsonContains('payload->trx_id', $trxId)
                ->exists()) {
            return response('already processed', 200);
        }

        // Save event as NOT valid yet — will be validated via check-transaction (F-03)
        $event = PaymentEvent::create([
            'payment_id' => $payment->id,
            'event_type' => 'notify',
            'payload' => $payload,
            'is_valid' => false,
        ]);

        // Idempotency: if order already PAID, do nothing
        if ($order->payment_status === 'PAID') {
            return response('ok', 200);
        }

        // Map statuses
        if ($statusCode === '1' || $status === 'berhasil' || $status === 'success') {
            // F-03: Server-to-server verification before setting PAID
            if ($trxId) {
                $verified = $this->verifyTransaction($trxId);
                if (!$verified) {
                    Log::warning('iPaymu notify verification FAILED', [
                        'order' => $reference,
                        'trx_id' => $trxId,
                    ]);
                    return response('verification failed', 200);
                }
                // Mark event as valid after successful verification
                $event->update(['is_valid' => true]);
            } else {
                Log::warning('iPaymu notify success but no trx_id for verification', [
                    'order' => $reference,
                ]);
                // Without trx_id we cannot verify — reject
                return response('missing trx_id', 200);
            }

            $order->update([
                'payment_status' => 'PAID',
                'order_status' => 'DIPROSES',
            ]);
            $payment->update([
                'status' => 'PAID',
                'gateway_trx_id' => $trxId,
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

    /**
     * Verify transaction via iPaymu Check Transaction endpoint.
     * Returns true only if iPaymu confirms status_code == 1.
     */
    private function verifyTransaction(string $trxId): bool
    {
        try {
            $res = $this->ipaymu->checkTransaction($trxId);
            $body = $res['body'] ?? [];

            // iPaymu Check Transaction returns Status:200 and Data with StatusCode
            $apiStatus = (int)($body['Status'] ?? 0);
            if ($apiStatus !== 200) {
                Log::warning('iPaymu checkTransaction API error', [
                    'trx_id' => $trxId,
                    'status' => $apiStatus,
                ]);
                return false;
            }

            // Check the transaction status_code in the response data
            $txStatusCode = (int)($body['Data']['StatusCode'] ?? -1);
            return $txStatusCode === 1; // 1 = berhasil/success
        } catch (\Throwable $e) {
            Log::error('iPaymu checkTransaction exception', [
                'trx_id' => $trxId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
