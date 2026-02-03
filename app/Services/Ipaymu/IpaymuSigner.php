<?php

namespace App\Services\Ipaymu;

class IpaymuSigner
{
    /**
     * Signature v2 (iPaymu):
     * StringToSign = HTTPMethod:VaNumber:lowercase(sha256(RequestBodyJson)):ApiKey
     * Signature = HMAC-SHA256(StringToSign, ApiKey)
     */
    public function makeSignature(string $httpMethod, string $va, string $apiKey, string $jsonBody): string
    {
        $method = strtoupper($httpMethod);
        $bodyHash = strtolower(hash('sha256', $jsonBody));
        $stringToSign = "{$method}:{$va}:{$bodyHash}:{$apiKey}";
        return hash_hmac('sha256', $stringToSign, $apiKey);
    }

    public function timestamp(): string
    {
        // iPaymu header timestamp format: YYYYMMDDhhmmss
        // Pakai WIB agar tidak meleset validasi jam
        return now('Asia/Jakarta')->format('YmdHis');
    }
}
