<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cafe\TableSessionController;
use App\Http\Controllers\Cafe\MenuController;
use App\Http\Controllers\Cafe\CartController;
use App\Http\Controllers\Cafe\CheckoutController;
use App\Http\Controllers\Cafe\PaymentController;
use App\Http\Controllers\Cafe\OrderController;
use App\Http\Controllers\Cafe\FeedbackController;
use App\Http\Controllers\Cafe\IpaymuWebhookController;

Route::middleware(['web'])->group(function () {
    // Note: /t/{token} route is defined in web.php to keep QR URLs at root level
    Route::get('/table/{tableNo}', [TableSessionController::class, 'enterByNumber'])->name('cafe.table.enter.number');

    Route::middleware(['cafe.table'])->group(function () {
        Route::get('/', fn () => redirect()->route('cafe.menu'))->name('cafe.home');
        Route::get('/menu', [MenuController::class, 'index'])->name('cafe.menu');
        Route::get('/product/{product:slug}', [MenuController::class, 'show'])->name('cafe.product.show');

        Route::get('/cart', [CartController::class, 'index'])->name('cafe.cart');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cafe.cart.add');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cafe.cart.update');
        Route::post('/cart/remove', [CartController::class, 'remove'])->name('cafe.cart.remove');

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('cafe.checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('cafe.checkout.store');

        Route::get('/pay/{order:order_code}', [PaymentController::class, 'redirect'])->name('cafe.pay');

        Route::get('/order/{order:order_code}', [OrderController::class, 'show'])->name('cafe.order.show');
        Route::get('/order/{order:order_code}/status', [OrderController::class, 'statusJson'])->middleware('throttle:60,1')->name('cafe.order.status');
        Route::post('/order/{order:order_code}/feedback', [FeedbackController::class, 'store'])->name('cafe.order.feedback');
        Route::post('/order/{order:order_code}/cancel', [OrderController::class, 'cancel'])->name('cafe.order.cancel');

        Route::get('/history', [OrderController::class, 'history'])->name('cafe.history');
    });
});
