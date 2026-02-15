<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminModifierController;

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'show'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:10,1')->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['web','admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::get('/orders/{order}/receipt', [AdminOrderController::class, 'receipt'])->name('admin.orders.receipt');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'setStatus'])->name('admin.orders.status');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.delete');

        // Menu
        Route::get('/menu', [AdminMenuController::class, 'index'])->name('admin.menu');
        Route::get('/menu/categories', [AdminMenuController::class, 'categories'])->name('admin.categories');
        Route::post('/menu/categories', [AdminMenuController::class, 'storeCategory'])->name('admin.categories.store');
        Route::post('/menu/categories/{category}/delete', [AdminMenuController::class, 'deleteCategory'])->name('admin.categories.delete');

        Route::get('/menu/products', [AdminMenuController::class, 'products'])->name('admin.products');
        Route::get('/menu/products/create', [AdminMenuController::class, 'createProduct'])->name('admin.products.create');
        Route::post('/menu/products', [AdminMenuController::class, 'storeProduct'])->name('admin.products.store');
        Route::get('/menu/products/{product}/edit', [AdminMenuController::class, 'editProduct'])->name('admin.products.edit');
        Route::post('/menu/products/{product}', [AdminMenuController::class, 'updateProduct'])->name('admin.products.update');
        Route::post('/menu/products/{product}/delete', [AdminMenuController::class, 'deleteProduct'])->name('admin.products.delete');
        Route::post('/menu/products/{product}/toggle-bestseller', [AdminMenuController::class, 'toggleBestSeller'])->name('admin.products.toggle-bestseller');

        // Modifiers
        Route::get('/modifiers', [AdminModifierController::class, 'index'])->name('admin.modifiers');
        Route::get('/modifiers/create', [AdminModifierController::class, 'create'])->name('admin.modifiers.create');
        Route::post('/modifiers', [AdminModifierController::class, 'store'])->name('admin.modifiers.store');
        Route::get('/modifiers/{modGroup}/edit', [AdminModifierController::class, 'edit'])->name('admin.modifiers.edit');
        Route::post('/modifiers/{modGroup}', [AdminModifierController::class, 'update'])->name('admin.modifiers.update');
        Route::post('/modifiers/{modGroup}/delete', [AdminModifierController::class, 'destroy'])->name('admin.modifiers.delete');
        
        // Modifier Options
        Route::post('/modifiers/{modGroup}/options', [AdminModifierController::class, 'storeOption'])->name('admin.modifiers.options.store');
        Route::post('/modifier-options/{option}', [AdminModifierController::class, 'updateOption'])->name('admin.modifiers.options.update');
        Route::post('/modifier-options/{option}/delete', [AdminModifierController::class, 'destroyOption'])->name('admin.modifiers.options.delete');

        // Tables & QR
        Route::get('/tables', [AdminTableController::class, 'index'])->name('admin.tables');
        Route::post('/tables', [AdminTableController::class, 'store'])->name('admin.tables.store');
        Route::post('/tables/{table}/rotate-token', [AdminTableController::class, 'rotateToken'])->name('admin.tables.rotate');
        Route::get('/tables/{table}/qr.png', [AdminTableController::class, 'qrPng'])->name('admin.tables.qr');
        Route::get('/tables/{table}/download/{format}', [AdminTableController::class, 'downloadQr'])->name('admin.tables.qr.download');

        // Feedback
        Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback');
        Route::post('/feedback/{feedback}/toggle', [AdminFeedbackController::class, 'toggle'])->name('admin.feedback.toggle');

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'update'])->name('admin.settings.update');
        Route::post('/settings/close-order', [\App\Http\Controllers\Admin\AdminSettingController::class, 'updateCloseOrder'])->name('admin.settings.close-order');
        Route::post('/settings/toggle-cafe', [\App\Http\Controllers\Admin\AdminSettingController::class, 'toggleCafe'])->name('admin.settings.toggle-cafe');
        Route::post('/settings/receipt', [\App\Http\Controllers\Admin\AdminSettingController::class, 'updateReceipt'])->name('admin.settings.receipt');

        // Banners
        Route::get('/banners', [\App\Http\Controllers\Admin\AdminBannerController::class, 'index'])->name('admin.banners');
        Route::post('/banners', [\App\Http\Controllers\Admin\AdminBannerController::class, 'store'])->name('admin.banners.store');
        Route::post('/banners/{banner}/toggle', [\App\Http\Controllers\Admin\AdminBannerController::class, 'toggleActive'])->name('admin.banners.toggle');
        Route::post('/banners/{banner}/delete', [\App\Http\Controllers\Admin\AdminBannerController::class, 'destroy'])->name('admin.banners.delete');
        Route::post('/banners/reorder', [\App\Http\Controllers\Admin\AdminBannerController::class, 'reorder'])->name('admin.banners.reorder');
    });
});
