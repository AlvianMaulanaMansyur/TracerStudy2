<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Alumni;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function getUserData()
    {
        $currentUser = Auth::guard('alumni')->user();

        return view('layouts.partials.alumni.navbar', compact('currentUser'));
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function show()
{
    // Ambil data pengguna yang sedang login
    $currentUser = Auth::guard('alumni')->user();

    // Ambil data alumni berdasarkan pengguna yang login
    $profil = Alumni::findOrFail($currentUser->id);

    // dd($profil);
    // Kirim data ke view
    return view('alumni.profil', compact('profil'));
}
public function editProfil()
    {
        // Ambil data pengguna yang sedang login
        $currentUser = Auth::guard('alumni')->user();

        // Kirim data ke view edit
        return view('alumni.editProfil', compact('currentUser'));
    }

    public function updateProfil(Request $request)
    {
        // Ambil data pengguna yang sedang login
        $currentUser = Auth::guard('alumni')->user();

        // Validasi data input
        $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Perbarui data alumni
        $currentUser->nama_alumni = $request->input('nama_alumni');
        $currentUser->email = $request->input('email');
        $currentUser->no_telepon = $request->input('no_telepon');




        // Jika ada foto baru yang diunggah
    if ($request->hasFile('foto_profil')) {

        // Hapus foto lama jika ada
        if ($currentUser ->foto_profil) {
            Storage::disk('public')->delete($currentUser ->foto_profil);
        }

        // Simpan foto baru
        $path = $request->file('foto_profil')->store('alumni_foto_profil', 'public');
        $currentUser ->foto_profil = $path;
    }

        // // Jika ada foto baru yang diunggah
        // if ($request->hasFile('foto_profil')) {
        //     $path = $request->file('foto_profil')->store('alumni_foto_profil', 'public');
        //     $currentUser->foto_profil = $path;
        // }

        // Simpan perubahan
        $currentUser->save();

        // Redirect dengan pesan sukses
        return redirect()->route('alumni.profil')->with('success', 'Profil berhasil diperbarui.');
    }
    
}
