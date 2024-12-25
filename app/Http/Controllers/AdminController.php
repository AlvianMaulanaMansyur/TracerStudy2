<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard'); // This view should be created for the dashboard page
    }

    public function tampilAlumni()
    {
        $totalAlumni = Alumni::count();
        $alumni = Alumni::paginate(10);
        return view('admin.dashboard', compact('alumni', 'totalAlumni'));
    }
    public function updateAlumni(Request $request)
    {
        // Validasi data
    $request->validate([
        'nim' => 'required|string|max:255',
        'nama_alumni' => 'required|string|max:255',
        'angkatan' => 'required|string|max:255',
    ]);

    // Temukan data alumni berdasarkan NIM
    $alumni = Alumni::where('nim', $request->nim)->first();

    if ($alumni) {
        // Update data alumni
        $alumni->nama_alumni = $request->nama_alumni;
        $alumni->angkatan = $request->angkatan;
        $alumni->save();

        return response()->json(['success' => 'Data alumni berhasil diperbarui']);
    } else {
        return response()->json(['error' => 'Alumni tidak ditemukan'], 404);
    }
    }
}
