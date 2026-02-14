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
