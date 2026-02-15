<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class AdminSettingController extends Controller
{
    public function index()
    {
        $customPath = public_path('custom_notification.mp3');
        $hasCustom = File::exists($customPath);

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.settings', [
            'hasCustom' => $hasCustom,
            'soundUrl' => $hasCustom ? asset('custom_notification.mp3') : asset('assets/audio/default.mp3'),
            'categories' => $categories,
            'cafeIsOpen' => Setting::isCafeOpen(),
            // Receipt settings
            'receiptLogo'           => Setting::getValue('receipt_logo'),
            'receiptShowLogo'       => Setting::getValue('receipt_show_logo', '0'),
            'receiptCafeLocation'   => Setting::getValue('receipt_cafe_location'),
            'receiptFooterText'     => Setting::getValue('receipt_footer_text', 'Terima kasih! 🙏'),
            'receiptTheme'          => Setting::getValue('receipt_theme', 'normal'),
            'receiptShowStatus'     => Setting::getValue('receipt_show_status_badges', '1'),
            'receiptShowCustomer'   => Setting::getValue('receipt_show_customer_info', '1'),
            'receiptShowPayment'    => Setting::getValue('receipt_show_payment_method', '1'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'notification_sound' => 'required|file|mimes:mp3,wav|max:2048',
        ]);

        if ($request->hasFile('notification_sound')) {
            $file = $request->file('notification_sound');
            $file->move(public_path(), 'custom_notification.mp3');
            
            return back()->with('success', 'Suara notifikasi berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }

    public function updateReceipt(Request $request)
    {
        $request->validate([
            'receipt_logo'          => 'nullable|image|mimes:png,jpg,jpeg,webp|max:1024',
            'receipt_cafe_location' => 'nullable|string|max:500',
            'receipt_footer_text'   => 'nullable|string|max:200',
            'receipt_theme'         => 'required|in:normal,bw',
        ]);

        // Handle logo upload
        if ($request->hasFile('receipt_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::getValue('receipt_logo');
            if ($oldLogo && File::exists(public_path($oldLogo))) {
                File::delete(public_path($oldLogo));
            }

            $file = $request->file('receipt_logo');
            $filename = 'receipt_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::setValue('receipt_logo', 'uploads/' . $filename);
        }

        // Handle logo removal
        if ($request->boolean('remove_logo')) {
            $oldLogo = Setting::getValue('receipt_logo');
            if ($oldLogo && File::exists(public_path($oldLogo))) {
                File::delete(public_path($oldLogo));
            }
            Setting::setValue('receipt_logo', null);
            Setting::setValue('receipt_show_logo', '0');
        }

        // Save text settings
        Setting::setValue('receipt_show_logo', $request->boolean('receipt_show_logo') ? '1' : '0');
        Setting::setValue('receipt_cafe_location', $request->input('receipt_cafe_location'));
        Setting::setValue('receipt_footer_text', $request->input('receipt_footer_text', 'Terima kasih! 🙏'));
        Setting::setValue('receipt_theme', $request->input('receipt_theme', 'normal'));
        Setting::setValue('receipt_show_status_badges', $request->boolean('receipt_show_status_badges') ? '1' : '0');
        Setting::setValue('receipt_show_customer_info', $request->boolean('receipt_show_customer_info') ? '1' : '0');
        Setting::setValue('receipt_show_payment_method', $request->boolean('receipt_show_payment_method') ? '1' : '0');

        return back()->with('success', 'Pengaturan struk berhasil disimpan!');
    }

    public function updateCloseOrder(Request $request)
    {
        $request->validate([
            'close_order_time' => 'required|array',
            'close_order_time.*' => 'nullable|date_format:H:i',
        ]);

        $times = $request->input('close_order_time', []);

        foreach ($times as $categoryId => $time) {
            Category::where('id', $categoryId)->update([
                'close_order_time' => $time ?: null,
            ]);
        }

        // Clear cached categories so changes take effect immediately
        Cache::forget('menu_categories');

        return back()->with('success', 'Pengaturan close order berhasil disimpan!');
    }

    public function toggleCafe(Request $request)
    {
        $currentStatus = Setting::isCafeOpen();
        Setting::setValue('cafe_is_open', $currentStatus ? '0' : '1');

        // Clear menu cache
        Cache::forget('menu_categories');

        $statusText = $currentStatus ? 'DITUTUP' : 'DIBUKA';
        return back()->with('success', "Cafe berhasil {$statusText}!");
    }
}
