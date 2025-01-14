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
        $prodiIds = [10, 1, 20]; // Ganti dengan ID yang sesuai

        // Menentukan tahun saat ini
        $currentYear = date('Y');

        $jumlahMahasiswaAktif = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '>=', $currentYear) // Filter alumni yang sudah lulus
            ->select('prodi_id', DB::raw('count(*) as total'))
            ->groupBy('prodi_id')
            ->get();
        // Menghitung total alumni dari jumlahAlumni
        $totalMahasiswaAktif = $jumlahMahasiswaAktif->sum('total');

        // Menghitung jumlah alumni yang sudah lulus untuk setiap prodi_id
        $jumlahAlumni = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '<', $currentYear) // Filter alumni yang sudah lulus
            ->select('prodi_id', DB::raw('count(*) as total'))
            ->groupBy('prodi_id')
            ->get();

        // Menghitung total alumni dari jumlahAlumni
        $totalAlumni = $jumlahAlumni->sum('total');

        // Mengambil data angkatan per jurusan yang sudah lulus
        $angkatanPerJurusan = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '<', $currentYear) // Filter alumni yang sudah lulus
            ->select('prodi_id', 'angkatan', 'tahun_lulus', DB::raw('count(*) as total'))
            ->groupBy('prodi_id', 'angkatan', 'tahun_lulus') // Mengelompokkan berdasarkan prodi_id, angkatan, dan tahun_lulus
            ->get();

        // Mengambil data alumni yang sudah lulus dan mengelompokkan berdasarkan tahun lulus
        $angkatanPerTahunLulus = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '<', $currentYear) // Filter alumni yang sudah lulus
            ->select('tahun_lulus', DB::raw('count(*) as total'))
            ->groupBy('tahun_lulus') // Mengelompokkan berdasarkan tahun lulus
            ->get();

        // Mengambil data Mahasiswa 
        $angkatanPerTahun = Alumni::whereIn('prodi_id', $prodiIds)
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->get();

        return view('alumni.home', compact('jumlahAlumni', 'totalAlumni', 'angkatanPerJurusan', 'jumlahMahasiswaAktif', 'totalMahasiswaAktif', 'angkatanPerTahunLulus', 'angkatanPerTahun')); // Mengembalikan tampilan home alumni
    }
    public function statistik()
    {
        // Misalkan $prodiIds adalah array ID program studi yang ingin Anda ambil
        $prodiIds = [10, 1, 20]; // Ganti dengan ID yang sesuai

        // Menentukan tahun saat ini
        $currentYear = date('Y');

        $jumlahMahasiswaAktif = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '>=', $currentYear) // Filter alumni yang sudah lulus
            ->select('prodi_id', DB::raw('count(*) as total'))
            ->groupBy('prodi_id')
            ->get();
        // Menghitung total alumni dari jumlahAlumni
        $totalMahasiswaAktif = $jumlahMahasiswaAktif->sum('total');

        // Menghitung jumlah alumni yang sudah lulus untuk setiap prodi_id
        $jumlahAlumni = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '<', $currentYear) // Filter alumni yang sudah lulus
            ->select('prodi_id', DB::raw('count(*) as total'))
            ->groupBy('prodi_id')
            ->get();

        // Menghitung total alumni dari jumlahAlumni
        $totalAlumni = $jumlahAlumni->sum('total');

        // Mengambil data angkatan per jurusan yang sudah lulus
        $angkatanPerJurusan = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '<', $currentYear) // Filter alumni yang sudah lulus
            ->select('prodi_id', 'angkatan', 'tahun_lulus', DB::raw('count(*) as total'))
            ->groupBy('prodi_id', 'angkatan', 'tahun_lulus') // Mengelompokkan berdasarkan prodi_id, angkatan, dan tahun_lulus
            ->get();

        // Mengambil data alumni yang sudah lulus dan mengelompokkan berdasarkan tahun lulus
        $angkatanPerTahunLulus = Alumni::whereIn('prodi_id', $prodiIds)
            ->where('tahun_lulus', '<', $currentYear) // Filter alumni yang sudah lulus
            ->select('tahun_lulus', DB::raw('count(*) as total'))
            ->groupBy('tahun_lulus') // Mengelompokkan berdasarkan tahun lulus
            ->get();

        // Mengambil data Mahasiswa 
        $angkatanPerTahun = Alumni::whereIn('prodi_id', $prodiIds)
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->get();

        return view('alumni.statistik', compact('jumlahAlumni', 'totalAlumni', 'angkatanPerJurusan', 'jumlahMahasiswaAktif', 'totalMahasiswaAktif', 'angkatanPerTahunLulus', 'angkatanPerTahun')); // Mengembalikan tampilan home alumni
    }
    public function faq()
    {
        // Anda bisa mengambil data alumni dari database jika diperlukan
        // $alumni = Alumni::all(); // Contoh pengambilan data alumni

        return view('alumni.faq'); // Mengembalikan tampilan home alumni
    } 
    
}