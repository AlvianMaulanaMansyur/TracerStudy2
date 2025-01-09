<?php

namespace App\Http\Controllers;

use App\Models\Halaman;
use App\Models\Kuesioner;
use App\Models\Pertanyaan;
use App\Models\Jawaban_kuesioner;
use App\Models\Jawaban_logika;
use App\Models\Logika;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class KuesionerController extends Controller
{

    // public function index()
    // {
    //     $kuesioner = Kuesioner::with('pertanyaan')->get();
    //     return view('kuesioner.admin.index', compact('kuesioner'));
    // }

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
                'kuesioner_id' => 'required|string',
                'pages' => 'required|array',
                'pages.*.halaman_id' => 'required|string',
                'pages.*.judul_halaman' => 'required|string|max:255',
                'pages.*.deskripsi_halaman' => 'nullable|string',
                'questions' => 'required|array',
                'questions.*.kode_pertanyaan' => 'required|string',
                'questions.*.kode_induk' => 'nullable|string',
                'questions.*.tipe_pertanyaan' => 'required|string',
                'questions.*.teks_pertanyaan' => 'required|string',
                'questions.*.opsi_jawaban' => 'nullable|array',
                'questions.*.halaman_id' => 'required|string',
                'logics' => 'nullable|array',
                'logics.*.id' => 'nullable|string',
                'logics.*.pertanyaan_id' => 'nullable|string',
                'logics.*.option_name' => 'nullable|string',
                'logics.*.tipe_pertanyaan' => 'nullable|string',
                'logics.*.teks_pertanyaan' => 'nullable|string',
                'logics.*.opsi_jawaban' => 'nullable|array',
            ]);

            // Simpan data kuesioner
            $adminId = Auth::guard('admin')->user()->id;

            // Buat slug dari judul kuesioner
            $slug = Str::slug($request->judul_kuesioner);

            // Pastikan slug unik
            $originalSlug = $slug;
            $count = 1;

            while (Kuesioner::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count; // Tambahkan angka ke slug
                $count++;
            }

            $kuesioner = Kuesioner::create([
                'id' => $request->kuesioner_id,
                'judul_kuesioner' => $request->judul_kuesioner,
                'slug' => $slug, // Menyimpan slug
                'admin_id' => $adminId,
            ]);

            // Array untuk menyimpan ID pertanyaan yang sudah disimpan
            $savedQuestionIds = [];

            // Loop through each page and save it
            foreach ($request->pages as $page) {
                // Simpan data halaman
                Halaman::create([
                    'id' => $page['halaman_id'],
                    'judul_halaman' => $page['judul_halaman'],
                    'deskripsi_halaman' => $page['deskripsi_halaman'],
                ]);
            }

            // Save related questions for all pages
            foreach ($request->questions as $question) {
                // Cek apakah pertanyaan sudah ada
                if (!in_array($question['kode_pertanyaan'], $savedQuestionIds)) {
                    $dataPertanyaan = [
                        'id' => $question['kode_pertanyaan'],
                        'data_pertanyaan' => json_encode([
                            'tipe_pertanyaan' => $question['tipe_pertanyaan'],
                            'teks_pertanyaan' => $question['teks_pertanyaan'],
                            'opsi_jawaban' => $question['opsi_jawaban'],
                        ]),
                        'halaman_id' => $question['halaman_id'], // Menggunakan halaman_id dari pertanyaan
                        'kuesioner_id' => $kuesioner->id,
                    ];

                    // Simpan pertanyaan ke database
                    Pertanyaan::create($dataPertanyaan);

                    // Tambahkan ID pertanyaan ke array yang sudah disimpan
                    $savedQuestionIds[] = $question['kode_pertanyaan'];
                }
            }

            // Simpan data logika
            foreach ($request->logics as $logic) {
                // Pastikan pertanyaan_id ada di tabel pertanyaan
                $pertanyaanId = $logic['pertanyaan_id'];

                // Cek apakah pertanyaan dengan ID tersebut ada
                if (Pertanyaan::where('id', $pertanyaanId)->exists()) {
                    $dataLogika = [
                        'id' => $logic['id'],
                        'data_pertanyaan' => json_encode([
                            'option_name' => $logic['option_name'],
                            'tipe_pertanyaan' => $logic['tipe_pertanyaan'],
                            'teks_pertanyaan' => $logic['teks_pertanyaan'],
                            'opsi_jawaban' => $logic['opsi_jawaban'],
                        ]),
                        'pertanyaan_id' => $pertanyaanId, // Menggunakan pertanyaan_id yang benar
                    ];

                    // Simpan logika ke database
                    Logika::create($dataLogika);
                } else {
                    throw new \Exception("Pertanyaan dengan ID $pertanyaanId tidak ditemukan.");
                }
            }

            return response()->json(['message' => 'Kuesioner berhasil disimpan.'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function index()
    {
        // Ambil semua kuesioner dari database
        $kuesioners = Kuesioner::all();

        // Buat array untuk menyimpan ID halaman pertama dari setiap kuesioner
        $halamanPertamaIds = [];

        foreach ($kuesioners as $kuesioner) {
            // Ambil halaman pertama dari pertanyaan yang terkait dengan kuesioner
            $halamanPertama = Pertanyaan::where('kuesioner_id', $kuesioner->id)
                ->orderBy('halaman_id') // Mengurutkan berdasarkan halaman_id
                ->first(); // Ambil halaman pertama

            if ($halamanPertama) {
                $halamanPertamaIds[$kuesioner->id] = $halamanPertama->halaman_id; // Simpan ID halaman pertama
            } else {
                $halamanPertamaIds[$kuesioner->id] = null; // Jika tidak ada halaman, simpan null
            }
        }

        // Kembalikan tampilan dengan data kuesioner dan ID halaman pertama
        return view('kuesioner.admin.index', compact('kuesioners', 'halamanPertamaIds'));
    }

    public function AlumniKuesioner()
    {
        // Ambil semua kuesioner dari database
        $kuesioners = Kuesioner::all();

        // Buat array untuk menyimpan ID halaman pertama dari setiap kuesioner
        $halamanPertamaIds = [];

        foreach ($kuesioners as $kuesioner) {
            // Ambil halaman pertama dari pertanyaan yang terkait dengan kuesioner
            $halamanPertama = Pertanyaan::where('kuesioner_id', $kuesioner->id)
                ->orderBy('halaman_id') // Mengurutkan berdasarkan halaman_id
                ->first(); // Ambil halaman pertama

            if ($halamanPertama) {
                $halamanPertamaIds[$kuesioner->id] = $halamanPertama->halaman_id; // Simpan ID halaman pertama
            } else {
                $halamanPertamaIds[$kuesioner->id] = null; // Jika tidak ada halaman, simpan null
            }
        }

        // Kembalikan tampilan dengan data kuesioner dan ID halaman pertama
        return view('kuesioner.alumni.index', compact('kuesioners', 'halamanPertamaIds'));
    }

    public function showPage($slug, $halamanId)
    {
        // Ambil halaman berdasarkan ID
        $halaman = Halaman::findOrFail($halamanId);

        // Ambil semua pertanyaan yang terkait dengan halaman
        $pertanyaan = Pertanyaan::with(['logika'])
            ->where('halaman_id', $halamanId)
            ->get();

        // Ambil kuesioner berdasarkan slug
        $kuesioner = Kuesioner::where('slug', $slug)->firstOrFail(); // Menggunakan slug untuk mencari kuesioner

        // Ambil semua halaman yang terkait dengan kuesioner
        $halamanSemua = Halaman::whereIn('id', Pertanyaan::where('kuesioner_id', $kuesioner->id)->pluck('halaman_id'))->get();

        return view('kuesioner.admin.show', compact('halaman', 'pertanyaan', 'halamanSemua', 'kuesioner'));
    }

    public function AlumniKuesionerPage($slug, $halamanId)
    {
        // Ambil halaman berdasarkan ID
        $halaman = Halaman::findOrFail($halamanId);

        // Ambil semua pertanyaan yang terkait dengan halaman
        $pertanyaan = Pertanyaan::with(['logika'])
            ->where('halaman_id', $halamanId)
            ->get();

        // Ambil kuesioner berdasarkan slug
        $kuesioner = Kuesioner::where('slug', $slug)->firstOrFail(); // Menggunakan slug untuk mencari kuesioner

        // Ambil semua halaman yang terkait dengan kuesioner
        $halamanSemua = Halaman::whereIn('id', Pertanyaan::where('kuesioner_id', $kuesioner->id)->pluck('halaman_id'))->get();

        return view('kuesioner.alumni.show', compact('halaman', 'pertanyaan', 'halamanSemua', 'kuesioner'));
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


    public function saveChoice(Request $request)
    {
        $request->validate([
            'choice' => 'required|string',
        ]);

        // Simpan pilihan di session
        session(['user_choice' => $request->choice]);

        return response()->json(['success' => true]);
    }

    public function destroySession()
    {
        session()->forget('user_choice'); // Menghapus session tertentu
        session()->forget('history'); // Menghapus session tertentu

        return redirect()->back()->with('success', 'Sesi berhasil dihapus.');
    }

    public function ShowKuesionerForAlumni($id, Request $request)
    {
        $kuesioner = Kuesioner::findOrFail($id);
        $currentPage = $request->input('page', 1);

        // Ambil semua pertanyaan yang terkait dengan kuesioner ini
        $pertanyaan = $kuesioner->pertanyaan()->get();

        // Hitung total halaman di semua pertanyaan
        $totalPages = $pertanyaan->max(function ($item) {
            $data = json_decode($item->data_pertanyaan);
            return isset($data->halaman) ? (int) $data->halaman : 0; // Pastikan nilai default
        });

        // Filter pertanyaan berdasarkan halaman
        $filteredQuestions = $pertanyaan->filter(function ($item) use ($currentPage) {
            $data = json_decode($item->data_pertanyaan);
            return isset($data->halaman) && $data->halaman == $currentPage;
        });

        return view('kuesioner.alumni.show', compact('kuesioner', 'filteredQuestions', 'currentPage', 'totalPages', 'pertanyaan'));
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
                // 'judul_kuesioner' => 'required|string|max:255',
                // 'questions' => 'required|array',
                // 'questions.*.teks_pertanyaan' => 'required|string',
                // 'questions.*.tipe_pertanyaan' => 'required|string|in:text,checkbox,radio',
                // 'questions.*.opsi_jawaban' => 'nullable|array',
                // 'questions.*.opsi_jawaban.*' => 'nullable|string',
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
                    'halaman' => $question['halaman'],
                    'logika' => $question['logika'] ?? null, // Menyimpan logika jika ada
                ]);
                $pertanyaan->save();
            }

            return response()->json(['message' => 'Kuesioner berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $kuesioner = Kuesioner::findOrFail($id); // Temukan kuesioner berdasarkan ID
        $kuesioner->delete(); // Hapus kuesioner

        return redirect()->route('kuesioner.index')->with('success', 'Kuesioner berhasil dihapus.');
    }

    public function submit(Request $request)
{
    try {
        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'nullable',
            'jawaban.logika' => 'nullable|array',
            'jawaban.logika.*' => 'nullable',
        ]);
        

        $alumniId = Auth::guard('alumni')->user()->id;

        foreach ($request->jawaban as $pertanyaanId => $jawaban) {
            // Cek apakah $pertanyaanId valid
            if ($pertanyaanId === 'logika') {
                continue; // Lewati logika untuk proses terpisah
            }
            
            $pertanyaan = Pertanyaan::find($pertanyaanId);
        
            if (!$pertanyaan) {
                return response()->json([
                    'error' => "Pertanyaan ID '$pertanyaanId' tidak valid."
                ], 422);
            }
        
            if (is_array($jawaban)) {
                foreach ($jawaban as $jawabanItem) {
                    Jawaban_kuesioner::create([
                        'jawaban' => $jawabanItem,
                        'alumni_id' => $alumniId,
                        'pertanyaan_id' => $pertanyaan->id,
                    ]);
                }
            } else {
                Jawaban_kuesioner::create([
                    'jawaban' => $jawaban,
                    'alumni_id' => $alumniId,
                    'pertanyaan_id' => $pertanyaan->id,
                ]);
            }
        }
        
        if (isset($request->jawaban['logika'])) {
            foreach ($request->jawaban['logika'] as $logikaId => $logikaJawaban) {
                // Validasi logika_id
                $logika = Logika::find($logikaId);
        
                if (!$logika) {
                    return response()->json([
                        'error' => "Logika ID '$logikaId' tidak valid."
                    ], 422);
                }
        
                Jawaban_logika::create([
                    'logika_id' => $logika->id,
                    'jawaban' => $logikaJawaban,
                ]);
            }
        }
        

        return response()->json(['success' => 'Jawaban berhasil disimpan!']);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validasi gagal.',
            'details' => $e->errors(),
        ], 422);
    } catch (\Illuminate\Database\QueryException $e) {
        \Log::error('Database error', [
            'message' => $e->getMessage(),
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings(),
        ]);

        return response()->json([
            'error' => 'Terjadi kesalahan pada database.',
            'details' => $e->getMessage(),
        ], 500);
    } catch (\Exception $e) {
        \Log::error('Error saving answers', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'error' => 'Terjadi kesalahan saat menyimpan jawaban.',
            'details' => $e->getMessage(),
        ], 500);
    }
}



    public function statistik($kuesionerId)
    {
        // Ambil semua pertanyaan untuk kuesioner tertentu
        $pertanyaan = Pertanyaan::where('kuesioner_id', $kuesionerId)->get();

        // Ambil semua jawaban untuk pertanyaan yang terkait dengan kuesioner
        $jawaban = Jawaban_kuesioner::whereIn('pertanyaan_id', $pertanyaan->pluck('id'))->get();

        // Proses data untuk statistik
        $statistik = [];
        foreach ($pertanyaan as $pertanyaanItem) {
            $statistik[$pertanyaanItem->id] = [
                'pertanyaan' => $pertanyaanItem->data_pertanyaan,
                'jawaban' => [],
            ];
        }

        foreach ($jawaban as $jawabanItem) {
            $pertanyaanId = $jawabanItem->pertanyaan_id;
            $jawabanValue = $jawabanItem->jawaban;

            if (!isset($statistik[$pertanyaanId]['jawaban'][$jawabanValue])) {
                $statistik[$pertanyaanId]['jawaban'][$jawabanValue] = 0;
            }
            $statistik[$pertanyaanId]['jawaban'][$jawabanValue]++;
        }


        return view('kuesioner.admin.statistik', compact('statistik'));
    }
}
