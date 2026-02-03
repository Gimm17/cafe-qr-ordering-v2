<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\QrService;
use App\Services\Ipaymu\IpaymuSigner;
use App\Services\Ipaymu\IpaymuClient;

class CafeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class, fn($app) => new CartService());
        $this->app->singleton(OrderService::class, fn($app) => new OrderService());
        $this->app->singleton(QrService::class, fn($app) => new QrService());

        $this->app->singleton(IpaymuSigner::class, fn($app) => new IpaymuSigner());
        $this->app->singleton(IpaymuClient::class, fn($app) => new IpaymuClient($app->make(IpaymuSigner::class)));
    }

    public function boot(): void {}
}
