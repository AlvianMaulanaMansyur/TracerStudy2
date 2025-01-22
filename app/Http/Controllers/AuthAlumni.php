<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;


class AuthAlumni extends Controller
{
    public function showLoginForm()
    {
        return view('alumni.login');
    }



public function login(Request $request)
{
    // Validasi input
    $request->validate([
        'nim' => 'required',
        'password' => 'required',
    ]);

    // Key untuk throttle berdasarkan NIM dan IP
    $key = $this->throttleKey($request);

    // Cek apakah sudah melebihi batas percobaan login
    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);

        // Kirim pesan ke halaman view menggunakan session flash
        return redirect()->back()->with('tooManyAttempts', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.");
    }

    // Dapatkan data alumni berdasarkan NIM
    $alumni = DB::table('alumni')->where('nim', $request->nim)->first();

    if (!$alumni) {
        RateLimiter::hit($key, 60); // Tambahkan percobaan gagal
        return back()->withErrors([
            'nim' => 'NIM tidak ditemukan.',
        ]);
    }

    // Coba login
    if (Auth::guard('alumni')->attempt($request->only('nim', 'password'))) {
        RateLimiter::clear($key); // Bersihkan percobaan jika login berhasil

        // Periksa apakah password masih default
        if ($alumni->password === 'password') {
            return redirect()->route('alumni.profile.edit')->with('alert', 'Silakan ubah password Anda.');
        }

        // Login berhasil
        $alumniId = Auth::guard('alumni')->user()->id;
        session(['alumniId' => $alumniId]);
        return redirect()->route('kuesioner.alumni.index');
    }

    // Tambahkan percobaan jika gagal login
    RateLimiter::hit($key, 60);

    return back()->withErrors([
        'nim' => 'NIM atau password salah.',
    ]);
}

/**
 * Mendapatkan key throttle berdasarkan NIM dan IP.
 */
protected function throttleKey(Request $request)
{
    return Str::lower($request->input('nim')).'|'.$request->ip();
}


    public function logoutSession(Request $request)
    {
        // Menghapus sesi alumniId
        $request->session()->forget('alumniId');

        // Melakukan logout
        Auth::guard('alumni')->logout();

        // Redirect ke halaman yang diinginkan setelah logout
        return redirect()->route('alumni.login')->with('message', 'You have been logged out successfully.');
    }
}
