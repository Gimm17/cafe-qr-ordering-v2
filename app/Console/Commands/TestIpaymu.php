<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Ipaymu\IpaymuClient;
use Illuminate\Support\Str;

class TestIpaymu extends Command
{
    protected $signature = 'test:ipaymu {orderCode?}';
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

        $orderCode = $this->argument('orderCode');
        
        if ($orderCode) {
            $order = \App\Models\Order::where('order_code', $orderCode)->first();
            if (!$order) {
                $this->error("Order not found: $orderCode");
                return;
            }
            
            $items = $order->items()->get();
            $products = $items->pluck('product_name')->map(fn($v) => (string)$v)->values()->all();
            $qtys = $items->pluck('qty')->map(fn($v) => (int)$v)->values()->all();
            $prices = $items->pluck('unit_price')->map(fn($v) => (int)$v)->values()->all();
            $descriptions = $items->pluck('note')->map(fn($v) => (string)($v ?? ''))->values()->all();
            
            $payload = [
                'account' => $cfg['va'],  // WAJIB
                'product' => $products,
                'qty' => $qtys,
                'price' => $prices,
                'description' => $descriptions,
                'returnUrl' => $cfg['return_url'].'?order_code='.$order->order_code,
                'cancelUrl' => $cfg['cancel_url'].'?order_code='.$order->order_code,
                'notifyUrl' => $cfg['notify_url'],
                'referenceId' => $order->order_code,
                'buyerName' => (string)$order->customer_name,
            ];
            
            $this->info("Using REAL order: $orderCode");
            $this->info("Payload:");
            dump($payload);
        } else {
            $refId = 'TEST-' . \Illuminate\Support\Str::random(6);
            $payload = [
                'account' => $cfg['va'],  // WAJIB
                'product' => ['Test Item'],
                'qty' => [1],
                'price' => [1000],
                'description' => ['Test Connection'],
                'returnUrl' => 'https://google.com',
                'cancelUrl' => 'https://google.com',
                'notifyUrl' => 'https://google.com',
                'referenceId' => $refId,
                'buyerName' => 'Test User',
            ];
            $this->info("Sending DUMMY payload for ref: $refId");
        }
        
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
