<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('alumni')->attempt($request->only('email', 'password'))) {
            return redirect()->route('kuesioner.alumni.index');
        }

        return back()->withErrors([
            'email' => 'NIP or password is incorrect.',
        ]);
    }
}
