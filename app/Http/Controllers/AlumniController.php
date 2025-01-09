<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class AlumniController extends Controller
{
    //
    public function index()
    {
        // Misalkan $prodiIds adalah array ID program studi yang ingin Anda ambil
        $prodiIds = [1, 2, 3]; // Ganti dengan ID yang sesuai

        // Menghitung jumlah alumni untuk setiap prodi_id
        $jumlahAlumni = Alumni::whereIn('prodi_id', $prodiIds)
            ->select('prodi_id', DB::raw('count(*) as total'))
            ->groupBy('prodi_id')
            ->get();

        // Menghitung total alumni dari jumlahAlumni
        $totalAlumni = $jumlahAlumni->sum('total');

        // Mengambil data angkatan per jurusan
        $angkatanPerJurusan = Alumni::whereIn('prodi_id', $prodiIds)
            ->select('prodi_id', 'angkatan', DB::raw('count(*) as total'))
            ->groupBy('prodi_id', 'angkatan') // Mengelompokkan berdasarkan prodi_id dan angkatan
            ->get();

        return view('alumni.home', compact('jumlahAlumni', 'totalAlumni', 'angkatanPerJurusan')); // Mengembalikan tampilan home alumni
    }
    public function statistik()
    {
        // Misalkan $prodiIds adalah array ID program studi yang ingin Anda ambil
        $prodiIds = [1, 2, 3]; // Ganti dengan ID yang sesuai

        // Menghitung jumlah alumni untuk setiap prodi_id
        $jumlahAlumni = Alumni::whereIn('prodi_id', $prodiIds)
            ->select('prodi_id', DB::raw('count(*) as total'))
            ->groupBy('prodi_id')
            ->get();

        // Menghitung total alumni dari jumlahAlumni
        $totalAlumni = $jumlahAlumni->sum('total');

        // Mengambil data angkatan per jurusan
        $angkatanPerJurusan = Alumni::whereIn('prodi_id', $prodiIds)
            ->select('prodi_id', 'angkatan', DB::raw('count(*) as total'))
            ->groupBy('prodi_id', 'angkatan') // Mengelompokkan berdasarkan prodi_id dan angkatan
            ->get();
            
        return view('alumni.statistik', compact('jumlahAlumni', 'totalAlumni', 'angkatanPerJurusan')); // Mengembalikan tampilan home alumni
        // Anda bisa mengambil data alumni dari database jika diperlukan
        // $alumni = Alumni::all(); // Contoh pengambilan data alumni
    }
    public function faq()
    {
        // Anda bisa mengambil data alumni dari database jika diperlukan
        // $alumni = Alumni::all(); // Contoh pengambilan data alumni

        return view('alumni.faq'); // Mengembalikan tampilan home alumni
    }
    public function getdata_alumni()
    {
        $alumni = Alumni::all();
        $alumniId = Auth::guard('alumni')->user()->id;
        $alumni = Alumni::findOrFail($id);
    }
}
