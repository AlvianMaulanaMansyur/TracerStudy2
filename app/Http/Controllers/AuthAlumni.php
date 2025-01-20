<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Dapatkan data alumni berdasarkan NIM
        $alumni = DB::table('alumni')->where('nim', $request->nim)->first();

        if (!$alumni) {
            return back()->withErrors([
                'nim' => 'NIM tidak ditemukan.',
            ]);
        }

        // Coba login
        if (Auth::guard('alumni')->attempt($request->only('nim', 'password'))) {
            // Periksa apakah password masih default
            if ($alumni->password === 'password') {
                // Redirect ke halaman profil untuk mengubah password
                return redirect()->route('alumni.profile.edit')->with('alert', 'Silakan ubah password Anda.');
            }

            // Login berhasil, redirect ke halaman dashboard
            $alumniId = Auth::guard('alumni')->user()->id;
            session(['alumniId' => $alumniId]);
            return redirect()->route('kuesioner.alumni.index');
        }

        // Jika login gagal, kembalikan ke halaman login
        return back()->withErrors([
            'nim' => 'NIM atau password salah.',
        ]);
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
