<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdmin extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('nip', 'password'))) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'nip' => 'NIP or password is incorrect.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login or home page
        return redirect('/admin/login'); // Ganti dengan URL login Anda
    }

    
}
