<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\search;

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
            // 'angkatan' => 'required|string|max:255',
        ]);

        // Temukan data alumni berdasarkan NIM
        $alumni = Alumni::where('nim', $request->nim)->first();

        if ($alumni) {
            // Update data alumni
            $alumni->nama_alumni = $request->nama_alumni;
            $alumni->angkatan = $request->angkatan;
            $alumni->gelombang_wisuda = $request->gelombang_wisuda;
            $alumni->save();

            return response()->json(['success' => 'Data alumni berhasil diperbarui']);
        } else {
            return response()->json(['error' => 'Alumni tidak ditemukan'], 404);
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $alumni = Alumni::where('nama_alumni', 'like', '%' . $search . '%')->orWhere('nim', 'like', '%' . $search . '%')->paginate(10);
        $totalAlumni = Alumni::count();


        return view('admin.dashboard', compact('alumni', 'totalAlumni'));
    }

    public function filter(Request $request)
    {
        $angkatan = $request->input('angkatan');
        $tahun_lulus = $request->input('tahun_lulus');
        $gelombang_wisuda = $request->input('gelombang_wisuda');

        $query = Alumni::query();

        if ($angkatan) {
            $query->where('angkatan', $angkatan);
        }

        if ($tahun_lulus) {
            $query->where('tahun_lulus', $tahun_lulus);
        }

        if ($gelombang_wisuda) {
            $query->where('gelombang_wisuda', $gelombang_wisuda);
        }

        $alumni = $query->paginate(10);
        $totalAlumni = Alumni::count();

        return view('admin.dashboard', compact('alumni', 'totalAlumni'));
    }


}
