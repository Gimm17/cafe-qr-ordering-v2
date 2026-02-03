<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function show()
    {
        return view('admin.login');
    }

    public function login(Request $req)
    {
        $data = $req->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        if (Auth::attempt($data, true)) {
            $req->session()->regenerate();

            if (!Auth::user()->is_admin) {
                Auth::logout();
                return back()->with('error', 'Akun ini bukan admin.');
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
