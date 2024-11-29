<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use App\Models\Pertanyaan;
use App\Models\Jawaban_kuesioner;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuesionerController extends Controller
{

    public function index()
    {
        $kuesioner = Kuesioner::with('pertanyaan')->get();
        return view('kuesioner.admin.index', compact('kuesioner'));
    }

    public function KuesionerForAlumni()
    {
        $kuesioner = Kuesioner::with('pertanyaan')->get();
        return view('kuesioner.alumni.index', compact('kuesioner'));
    }
    public function create()
    {
        return view('kuesioner.admin.create');
    }

    public function store(Request $request)
    {
        try {
            // Validasi data yang diterima
            $request->validate([
                'judul_kuesioner' => 'required|string|max:255',
                'questions.*.teks_pertanyaan' => 'required|string',
                'questions.*.tipe_pertanyaan' => 'required|string',
                'questions.*.opsi_jawaban' => 'nullable|array',
                'questions.*.opsi_jawaban.*' => 'nullable|string',
            ]);

            // Simpan data kuesioner ke database
            $kuesioner = Kuesioner::create([
                'admin_id' => 1,
                'judul_kuesioner' => $request->judul_kuesioner,
            ]);

            // Simpan pertanyaan yang terkait dengan kuesioner
            foreach ($request->questions as $question) {

                $data = [
                    'pertanyaan' => $question['teks_pertanyaan'],
                    'tipe_pertanyaan' => $question['tipe_pertanyaan'],
                    'opsi_jawaban' => $question['opsi_jawaban']
                ];

                Pertanyaan::create([
                    'data_pertanyaan' => json_encode($data),
                    'kuesioner_id' => $kuesioner->id,
                ]);
            }

            // Mengembalikan respons JSON
            return response()->json([
                'message' => 'Kuesioner berhasil disimpan.',
                'kuesioner' => $kuesioner,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $kuesioner = Kuesioner::with('pertanyaan')->findOrFail($id); // Mengambil kuesioner beserta pertanyaannya
        return view('kuesioner.admin.show', compact('kuesioner'));
    }

    public function ShowKuesionerForAlumni($id)
    {
        $kuesioner = Kuesioner::with('pertanyaan')->findOrFail($id); // Mengambil kuesioner beserta pertanyaannya
        return view('kuesioner.alumni.show', compact('kuesioner'));
    }

    public function edit($id)
    {
        $kuesioner = Kuesioner::with('pertanyaan')->findOrFail($id);
        return view('kuesioner.admin.edit', compact('kuesioner'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'judul_kuesioner' => 'required|string|max:255',
                'questions' => 'required|array',
                'questions.*.teks_pertanyaan' => 'required|string',
                'questions.*.tipe_pertanyaan' => 'required|string|in:text,multiple_choice',
                'questions.*.opsi_jawaban' => 'nullable|array',
                'questions.*.opsi_jawaban.*' => 'nullable|string',
            ]);

            // Temukan kuesioner berdasarkan ID
            $kuesioner = Kuesioner::findOrFail($id);
            $kuesioner->judul_kuesioner = $request->judul_kuesioner;
            $kuesioner->save();

            // Hapus semua pertanyaan yang ada
            $kuesioner->pertanyaan()->delete();

            // Tambahkan pertanyaan baru
            foreach ($request->questions as $question) {
                $pertanyaan = new Pertanyaan();
                $pertanyaan->kuesioner_id = $kuesioner->id;
                $pertanyaan->data_pertanyaan = json_encode([
                    'pertanyaan' => $question['teks_pertanyaan'],
                    'tipe_pertanyaan' => $question['tipe_pertanyaan'],
                    'opsi_jawaban' => $question['opsi_jawaban'] ?? [],
                ]);
                $pertanyaan->save();
            }

            return response()->json(['message' => 'Kuesioner berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    //     public function submit(Request $request, $kuesionerId)
    // {
    //     // Validasi input jawaban
    //     $request->validate([
    //         'jawaban' => 'required|array', // Pastikan jawaban ada dan merupakan array
    //     ]);

    //     // Ambil jawaban dari request
    //     $jawaban = $request->input('jawaban');

    //     // Proses jawaban sesuai kebutuhan, misalnya simpan ke database
    //     // Contoh: Jawaban bisa disimpan ke tabel jawaban_kuesioner
    //     foreach ($jawaban as $pertanyaanId => $jawabanPertanyaan) {
    //         // Simpan jawaban ke database
    //         // Jawaban bisa berupa string atau array (untuk multiple choice)
    //         // Misalnya, Anda bisa menggunakan model JawabanKuesioner
    //         Jawaban_kuesioner::create([
    //             'kuesioner_id' => $kuesionerId,
    //             'pertanyaan_id' => $pertanyaanId,
    //             'jawaban' => is_array($jawabanPertanyaan) ? json_encode($jawabanPertanyaan) : $jawabanPertanyaan,
    //         ]);
    //     }

    //     // Redirect atau tampilkan pesan sukses
    //     return redirect()->route('kuesioner.index')->with('success', 'Jawaban berhasil dikirim.');
    // }
}
