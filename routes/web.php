<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Cafe\TableSessionController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Table QR entry point (must be at root level for QR scan)
Route::get('/t/{token}', [TableSessionController::class, 'enter'])->name('cafe.table.enter');
