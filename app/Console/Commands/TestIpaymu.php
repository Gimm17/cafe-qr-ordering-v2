<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Ipaymu\IpaymuClient;
use Illuminate\Support\Str;

class TestIpaymu extends Command
{
    protected $signature = 'test:ipaymu';
    protected $description = 'Test iPaymu connection and create a dummy payment';

    public function handle(IpaymuClient $client)
    {
        $this->info('Testing iPaymu Connection...');
        
        $cfg = config('ipaymu');
        $this->table(
            ['Config Key', 'Value'],
            [
                ['Env', $cfg['env']],
                ['VA', $cfg['va']],
                ['API Key', substr($cfg['api_key'] ?? '', 0, 5) . '...'],
                ['URL', $cfg['env'] === 'production' ? $cfg['production_base'] : $cfg['sandbox_base']],
            ]
        );

        $refId = 'TEST-' . Str::random(6);
        $payload = [
            'product' => ['Test Item'],
            'qty' => [1],
            'price' => [1000], // Minimum amount usually
            'description' => ['Test Connection'],
            'returnUrl' => 'https://google.com',
            'cancelUrl' => 'https://google.com',
            'notifyUrl' => 'https://google.com',
            'referenceId' => $refId,
            'buyerName' => 'Test User',
        ];

        $this->info("Sending payload for ref: $refId");
        
        try {
            $res = $client->createRedirectPayment($payload);
            
            $this->info('HTTP Status: ' . $res['http_status']);
            $this->info('Response Body:');
            dump($res['body']);
            
            $url = $client->parseRedirectUrl($res['body']);
            if ($url) {
                $this->info("SUCCESS! Payment URL: $url");
            } else {
                $this->error("FAILED to parse Payment URL.");
            }
            
        } catch (\Exception $e) {
            $this->error("Exception: " . $e->getMessage());
        }
    }
}
