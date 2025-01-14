<?php

namespace App\Http\Controllers;

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
        // Validasi input
        $request->validate([
            'nim' => 'required',
            'password' => 'required',
        ]);

        // Coba untuk login
        if (Auth::guard('alumni')->attempt($request->only('nim', 'password'))) {
            // Login berhasil, redirect ke halaman yang diinginkan
            $alumniId = Auth::guard('alumni')->user()->id;
            session(['alumniId' => $alumniId]);
            return redirect()->route('kuesioner.alumni.index');
        }

        // Jika login gagal, kembali dengan pesan kesalahan
        return back()->withErrors([
            'nim' => 'NIM or password is incorrect.',
        ]);
    }
    public function logout(Request $request)
    {
        // Menghapus sesi alumniId
        $request->session()->forget('alumniId');

        // Melakukan logout
        Auth::guard('alumni')->logout();

        // Redirect ke halaman yang diinginkan setelah logout
        return redirect()->route('alumni.login')->with('message', 'You have been logged out successfully.');
    }
}
