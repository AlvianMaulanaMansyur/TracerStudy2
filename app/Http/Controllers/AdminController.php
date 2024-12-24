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

    // public function updateAlumni(Request $request, $nim)
    // {
    //     $validated = $request->validate([
    //         'nama_alumni' => 'required|string|max:255',
    //         'angkatan' => 'required|max:4'
    //     ]);

    //     $alumni = Alumni::where('nim', $nim)->first();

    //     if(!$alumni) {
    //         return response()->json(['success' => false, 'message' => 'Alumni Tidak Ditemukan'], 404);
    //     }

    //     $alumni->nama_alumni = $request->nama_alumni;
    //     $alumni->angkatan = $request->angkatan;
    //     $alumni->save();

    //     return response()->json(['success' => true, 'message' => 'Data Alumni Berhasil diperbaharui']);
    // }

    public function update (Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'nama_alumni' => 'required',
            'angkatan' => 'required',
        ]);

        $alumni = Alumni::where('nim', $request->nim)->first();
        if ($alumni) {
            $alumni->nama_alumni = $request->nama_alumni;
            $alumni->angkatan = $request->angkatan;
            $alumni->save();

            return response()->json(['success' => 'Data updated successfully.']);
        } else {
            return response()->json(['error' => 'Alumni not found.'], 404);
        }
    }

}
