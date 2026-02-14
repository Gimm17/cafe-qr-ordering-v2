<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminSettingController extends Controller
{
    public function index()
    {
        $customPath = public_path('custom_notification.mp3');
        $hasCustom = File::exists($customPath);
        
        return view('admin.settings', [
            'hasCustom' => $hasCustom,
            'soundUrl' => $hasCustom ? asset('custom_notification.mp3') : asset('assets/audio/default.mp3')
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'notification_sound' => 'required|file|mimes:mp3,wav|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('notification_sound')) {
            $file = $request->file('notification_sound');
            // Save directly to public folder as custom_notification.mp3
            $file->move(public_path(), 'custom_notification.mp3');
            
            return back()->with('success', 'Suara notifikasi berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }
}
