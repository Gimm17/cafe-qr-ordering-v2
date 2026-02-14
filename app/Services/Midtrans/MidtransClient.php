<?php

namespace App\Services\Midtrans;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransClient
{
    /**
     * Create a Snap token for the given order.
     * Returns ['token' => '...', 'redirect_url' => '...'] on success.
     */
    public function createSnapToken(Order $order): array
    {
        $cfg = config('midtrans');
        $serverKey = $cfg['server_key'];

        if (!$serverKey) {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum di-set di .env');
        }

        $items = $order->items()->get();

        $itemDetails = $items->map(fn($item) => [
            'id'       => (string) $item->product_id,
            'name'     => mb_substr((string) $item->product_name, 0, 50),
            'price'    => (int) round((float) $item->unit_price),
            'quantity' => (int) $item->qty,
        ])->values()->all();

        $grossAmount = collect($itemDetails)->sum(fn($i) => $i['price'] * $i['quantity']);

        $payload = [
            'transaction_details' => [
                'order_id'     => $order->order_code,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => (string) ($order->customer_name ?: 'Tamu'),
                'email'      => (string) ($order->customer_email ?: 'guest@cafe.local'),
            ],
            'callbacks' => [
                'finish' => route('cafe.order.show', ['order' => $order->order_code]),
            ],
            'enabled_payments' => [
                'gopay',
                'shopeepay',
                'other_qris',
                'dana',
            ],
            'expiry' => [
                'start_time' => $order->created_at->format('Y-m-d H:i:s O'),
                'unit'       => 'minutes',
                'duration'   => 10,
            ],
        ];

        $jsonBody = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        Log::debug('MIDTRANS createSnapToken', ['order' => $order->order_code]);

        $ch = curl_init($cfg['api_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonBody,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':'),
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('MIDTRANS cURL ERROR', ['error' => $error]);
            return ['token' => null, 'redirect_url' => null, 'error' => 'cURL Error: ' . $error];
        }

        $body = json_decode($response, true) ?? [];

        if ($httpStatus !== 201 || empty($body['token'])) {
            Log::warning('MIDTRANS snap token failed', [
                'status' => $httpStatus,
                'body'   => $body,
                'order'  => $order->order_code,
            ]);
            return [
                'token'        => null,
                'redirect_url' => null,
                'error'        => $body['error_messages'][0] ?? 'Gagal membuat token pembayaran.',
            ];
        }

        return [
            'token'        => $body['token'],
            'redirect_url' => $body['redirect_url'] ?? null,
        ];
    }

    /**
     * Verify notification signature from Midtrans.
     * signature_key = SHA512(order_id + status_code + gross_amount + server_key)
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('midtrans.server_key');
        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($expected, $signatureKey);
    }

    /**
     * Check transaction status directly from Midtrans API.
     */
    public function getStatus(string $orderId): ?array
    {
        $cfg = config('midtrans');
        $url = rtrim($cfg['status_url'], '/') . '/' . $orderId . '/status';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($cfg['server_key'] . ':'),
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 10,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
