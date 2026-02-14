<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('cafe')
                ->group(base_path('routes/cafe.php'));
            Route::middleware('web')
                ->group(base_path('routes/cafe_admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'cafe.table' => \App\Http\Middleware\EnsureTableContext::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);
        
        // Exclude Midtrans webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'midtrans/notify',
            '/midtrans/notify',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
