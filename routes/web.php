<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Cafe\TableSessionController;
use App\Http\Controllers\Cafe\PaymentController;
use App\Http\Controllers\Cafe\IpaymuWebhookController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Table QR entry point (must be at root level for QR scan)
Route::get('/t/{token}', [TableSessionController::class, 'enter'])->name('cafe.table.enter');

// iPaymu callbacks (must be at root level - no prefix)
Route::get('/ipaymu/return', [PaymentController::class, 'return'])->name('ipaymu.return');
Route::get('/ipaymu/cancel', [PaymentController::class, 'cancel'])->name('ipaymu.cancel');
Route::get('/ipaymu/notify', fn () => response('OK', 200))->name('ipaymu.notify.ping');
Route::post('/ipaymu/notify', [IpaymuWebhookController::class, 'handle'])->name('ipaymu.notify');
