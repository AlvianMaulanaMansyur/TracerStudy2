<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAlumni extends Controller
{
    public function showLoginForm()
    {
        return view('alumni.login'); // Pastikan Anda memiliki tampilan login
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Autentikasi berhasil, redirect ke halaman home alumni
            return redirect()->route('alumni.home');
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return redirect()->back()->withErrors(['email' => 'Email atau password salah.']);
    }
}
