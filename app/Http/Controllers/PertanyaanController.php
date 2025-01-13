<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Kuesioner;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    public function create(Kuesioner $kuesioner)
    {
        return view('pertanyaan.create', compact('kuesioner'));
    }

    public function store(Request $request, Kuesioner $kuesioner)
    {
        $request->validate([
            'teks_pertanyaan' => 'required|string',
            'tipe_pertanyaan' => 'required|string',
            'opsi_jawaban' => 'nullable|array', // Array of options if it's a multiple-choice question
        ]);
    
        // Prepare data as JSON
        $data = [
            'teks_pertanyaan' => $request->input('teks_pertanyaan'),
            'tipe_pertanyaan' => $request->input('tipe_pertanyaan'),
            'opsi_jawaban' => $request->input('opsi_jawaban') // optional
        ];
    
        // Save JSON data in the single `data` column
        $kuesioner->pertanyaan()->create(['data' => $data]);
    
        return redirect()->route('kuesioner.admin.show', $kuesioner);
    }

    public function tampilkanPertanyaan($halamanId, Request $request)
{
    $currentPage = $request->input('page', 1); // Halaman saat ini
    $pertanyaanPerHalaman = 1; // Jumlah pertanyaan per halaman (ubah sesuai kebutuhan)

    // Ambil pertanyaan berdasarkan halaman_id
    $pertanyaan = Pertanyaan::with(['halaman', 'logika'])
        ->where('halaman_id', $halamanId)
        ->paginate($pertanyaanPerHalaman); // Menggunakan pagination

    return view('pertanyaan.index', compact('pertanyaan', 'currentPage'));
}
}
