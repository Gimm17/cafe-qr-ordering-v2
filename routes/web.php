<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Cafe\TableSessionController;
use App\Http\Controllers\Cafe\MidtransWebhookController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Table QR entry point (must be at root level for QR scan)
Route::get('/t/{token}', [TableSessionController::class, 'enter'])->name('cafe.table.enter');

// Midtrans webhook (POST from Midtrans notification)
Route::post('/midtrans/notify', [MidtransWebhookController::class, 'handle'])->name('midtrans.notify');
