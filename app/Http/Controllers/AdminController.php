<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); // Ganti dengan view login admin yang kamu miliki
    }

    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('nip', 'password');

        // Coba autentikasi dengan guard admin
        if (Auth::guard('admin')->attempt($credentials)) {
            // Jika autentikasi berhasil, redirect ke halaman yang diinginkan
            return redirect()->intended(default: route('kuesioner.create'));
        }

        // Jika autentikasi gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login'); // Ganti dengan route login admin
    }
}
