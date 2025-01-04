<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlumniController extends Controller
{
    //
    public function index()
    {
        // Anda bisa mengambil data alumni dari database jika diperlukan
        // $alumni = Alumni::all(); // Contoh pengambilan data alumni

        return view('alumni.home'); // Mengembalikan tampilan home alumni
    }
    public function faq()
    {
        // Anda bisa mengambil data alumni dari database jika diperlukan
        // $alumni = Alumni::all(); // Contoh pengambilan data alumni

        return view('alumni.faq'); // Mengembalikan tampilan home alumni
    }
    public function statistik()
    {
        // Anda bisa mengambil data alumni dari database jika diperlukan
        // $alumni = Alumni::all(); // Contoh pengambilan data alumni

        return view('alumni.statistik'); // Mengembalikan tampilan home alumni
    }

    
}
