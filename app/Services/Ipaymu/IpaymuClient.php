<?php

namespace App\Services\Ipaymu;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class IpaymuClient
{
    public function __construct(
        private IpaymuSigner $signer
    ) {}

    public function createRedirectPayment(array $payload): array
    {
        $cfg = config('ipaymu');
        $va = $cfg['va'];
        $apiKey = $cfg['api_key'];

        if (!$va || !$apiKey) {
            throw new \RuntimeException('IPAYMU_VA / IPAYMU_API_KEY belum di-set di .env');
        }

        $base = $cfg['env'] === 'production' ? $cfg['production_base'] : $cfg['sandbox_base'];
        $url = rtrim($base, '/').'/api/v2/payment';

        $timestamp = $this->signer->timestamp();

        // Canonical JSON
        $jsonBody = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        if ($jsonBody === false) {
            throw new \RuntimeException('Gagal encode JSON payload iPaymu: ' . json_last_error_msg());
        }

        $signature = $this->signer->makeSignature('POST', $va, $apiKey, $jsonBody);

        // Debug log
        Log::info('IPAYMU DEBUG', [
            'url' => $url,
            'timestamp' => $timestamp,
            'va' => $va,
            'jsonBody' => $jsonBody,
            'bodyHash' => strtolower(hash('sha256', $jsonBody)),
            'signature' => $signature,
        ]);

        // Use native cURL instead of Laravel HTTP Client
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonBody,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'va: ' . $va,
                'signature: ' . $signature,
                'timestamp: ' . $timestamp,
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('IPAYMU cURL ERROR', ['error' => $error]);
            return [
                'http_status' => 0,
                'body' => ['Message' => 'cURL Error: ' . $error],
                'raw' => $error,
            ];
        }

        $body = json_decode($response, true) ?? [];

        return [
            'http_status' => $httpStatus,
            'body' => $body,
            'raw' => $response,
        ];
    }

    public function parseRedirectUrl(array $responseBody): ?string
    {
        // Expected: { Status:200, Data:{ SessionID:"..", Url:"https://.."} }
        return Arr::get($responseBody, 'Data.Url') ?? Arr::get($responseBody, 'Data.Url'.'') ?? null;
    }

    public function parseSessionId(array $responseBody): ?string
    {
        return Arr::get($responseBody, 'Data.SessionID');
    }
}
