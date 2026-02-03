<?php

namespace App\Services\Ipaymu;

use Illuminate\Support\Facades\Http;
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
        // Use standard json_encode (same as Laravel HTTP uses internally)
        $jsonBody = json_encode($payload);

        $signature = $this->signer->makeSignature('POST', $va, $apiKey, $jsonBody);

        // Send raw JSON body to ensure it exactly matches the signed content
        $res = Http::withHeaders([
            'Content-Type' => 'application/json',
            'va' => $va,
            'signature' => $signature,
            'timestamp' => $timestamp,
        ])->send('POST', $url, ['body' => $jsonBody]);

        return [
            'http_status' => $res->status(),
            'body' => $res->json(),
            'raw' => $res->body(),
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
