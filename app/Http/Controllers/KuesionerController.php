<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Chart_data;
use App\Models\Halaman;
use App\Models\Kuesioner;
use App\Models\Pertanyaan;
use App\Models\Jawaban_kuesioner;
use App\Models\Jawaban_logika;
use App\Models\Logika;
use App\Models\Status;
use App\Models\Status_data;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        // Ambil ID terakhir dari tabel pertanyaan
        $lastKuesioner = DB::table('kuesioner')->orderBy('id', 'desc')->first();
        $lastKuesionerId = $lastKuesioner ? (int) substr($lastKuesioner->id, 1) : 0; // Ambil angka terakhir dari ID pertanyaan

        // Ambil ID terakhir dari tabel pertanyaan
        $lastQuestion = DB::table('pertanyaan')->orderBy('id', 'desc')->first();
        $lastQuestionId = $lastQuestion ? (int) substr($lastQuestion->id, 1) : 0; // Ambil angka terakhir dari ID pertanyaan
    
        // Ambil ID terakhir dari tabel logika
        $lastLogic = DB::table('logika')->orderBy('id', 'desc')->first();
        $lastLogicId = $lastLogic ? (int) substr($lastLogic->id, 1) : 0; // Ambil angka terakhir dari ID logika
    
        // Ambil ID terakhir dari tabel halaman
        $lastPage = DB::table('halaman')->orderBy('id', 'desc')->first();
        $lastPageId = $lastPage ? (int) substr($lastPage->id, 1) : 0; // Ambil angka terakhir dari ID halaman
    
        return view('kuesioner.admin.create', [
            'lastKuesionerId' => $lastKuesionerId,
            'lastQuestionId' => $lastQuestionId,
            'lastLogicId' => $lastLogicId,
            'lastPageId' => $lastPageId,
        ]);
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

            // Memeriksa apakah slug sudah ada di database
            $originalSlug = $slug;
            $count = 1;

            while (Kuesioner::where('slug', $slug)->exists()) {
                // Jika slug sudah ada, tambahkan angka ke slug
                $slug = $originalSlug . '-' . $count;
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
                    'kuesioner_id' => $kuesioner->id,
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
                            'nomor_pertanyaan' =>$question['nomor_pertanyaan'],
                            'is_required' =>$question['is_required']
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
                            'nomor_pertanyaan' =>$question['opsi_jawaban']
                        ]),
                        'pertanyaan_id' => $pertanyaanId, // Menggunakan pertanyaan_id yang benar
                        'kuesioner_id' => $kuesioner->id,
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
    $alumniId = Auth::guard('alumni')->user()->id; // Ambil ID alumni yang sedang login

    // Buat array untuk menyimpan ID halaman pertama dari setiap kuesioner
    $halamanPertamaIds = [];
    $kuesionerSudahDiisi = []; // Array untuk menyimpan kuesioner yang sudah diisi oleh alumni

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

        // Cek apakah alumni sudah mengisi pertanyaan dalam kuesioner ini
        $pertanyaanIds = Pertanyaan::where('kuesioner_id', $kuesioner->id)->pluck('id')->toArray();
        $jawabanKuesioner = Jawaban_kuesioner::where('alumni_id', $alumniId)
            ->whereIn('pertanyaan_id', $pertanyaanIds)
            ->exists(); // Cek apakah ada jawaban untuk pertanyaan terkait

        if ($jawabanKuesioner) {
            $kuesionerSudahDiisi[$kuesioner->id] = true; // Tandai kuesioner ini sudah diisi
        }
    }

    return view('kuesioner.alumni.index', compact('kuesioners', 'halamanPertamaIds', 'kuesionerSudahDiisi'));
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

    // Urutkan pertanyaan berdasarkan nomor_pertanyaan
    $sortedQuestions = $pertanyaan->sortBy(function ($item) {
        $data = json_decode($item->data_pertanyaan);
        return (int) str_replace('Q', '', $data->nomor_pertanyaan); // Ekstrak angka dari nomor_pertanyaan
    });

    // Ambil kuesioner berdasarkan slug
    $kuesioner = Kuesioner::where('slug', $slug)->firstOrFail(); // Menggunakan slug untuk mencari kuesioner

    // Ambil semua halaman yang terkait dengan kuesioner
    $halamanSemua = Halaman::whereIn('id', Pertanyaan::where('kuesioner_id', $kuesioner->id)->pluck('halaman_id'))->get();

    // Dapatkan ID halaman terakhir
    $halamanTerakhir = $halamanSemua->last(); // Mengambil halaman terakhir dari koleksi

    // Cek apakah halaman saat ini adalah halaman terakhir
    $isHalamanTerakhir = $halaman->id === $halamanTerakhir->id;

    return view('kuesioner.alumni.show', compact('halaman', 'sortedQuestions', 'halamanSemua', 'kuesioner', 'isHalamanTerakhir'));
}

public function JawabanAlumni($slug)
{
    // Ambil kuesioner berdasarkan slug
    $kuesioner = Kuesioner::where('slug', $slug)->firstOrFail();

    // Ambil semua pertanyaan yang terkait dengan kuesioner
    $pertanyaan = Pertanyaan::with(['logika'])
        ->where('kuesioner_id', $kuesioner->id)
        ->get();

    // Ambil jawaban kuesioner untuk alumni yang sedang login
    $alumniId = Auth::guard('alumni')->user()->id;
    $jawabanKuesioner = Jawaban_kuesioner::where('alumni_id', $alumniId)
        ->whereIn('pertanyaan_id', $pertanyaan->pluck('id'))
        ->get();

    // Ambil jawaban logika untuk alumni yang sedang login
    $jawabanLogika = Jawaban_logika::where('alumni_id', $alumniId)
        ->whereIn('logika_id', $pertanyaan->flatMap(function ($item) {
            return $item->logika->pluck('id');
        }))
        ->get();

    return view('kuesioner.alumni.jawaban', compact('kuesioner', 'pertanyaan', 'jawabanKuesioner', 'jawabanLogika'));
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

        // Urutkan pertanyaan berdasarkan nomor_pertanyaan
    $sortedQuestions = $filteredQuestions->sortBy(function ($item) {
        $data = json_decode($item->data_pertanyaan);
        // Ekstrak angka dari nomor_pertanyaan
        return (int) str_replace('Q', '', $data->nomor_pertanyaan);
    });

    return view('kuesioner.alumni.show', compact('kuesioner', 'sortedQuestions', 'currentPage', 'totalPages', 'pertanyaan'));
    }

    public function edit($id)
{
    // Ambil ID terakhir dari tabel pertanyaan
    $lastKuesioner = DB::table('kuesioner')->orderBy('id', 'desc')->first();
    $lastKuesionerId = $lastKuesioner ? (int) substr($lastKuesioner->id, 1) : 0; // Ambil angka terakhir dari ID pertanyaan

    // Ambil ID terakhir dari tabel pertanyaan
    $lastQuestion = DB::table('pertanyaan')->orderBy('id', 'desc')->first();
    $lastQuestionId = $lastQuestion ? (int) substr($lastQuestion->id, 1) : 0; // Ambil angka terakhir dari ID pertanyaan

    // Ambil ID terakhir dari tabel logika
    $lastLogic = DB::table('logika')->orderBy('id', 'desc')->first();
    $lastLogicId = $lastLogic ? (int) substr($lastLogic->id, 1) : 0; // Ambil angka terakhir dari ID logika

    // Ambil ID terakhir dari tabel halaman
    $lastPage = DB::table('halaman')->orderBy('id', 'desc')->first();
    $lastPageId = $lastPage ? (int) substr($lastPage->id, 1) : 0; // Ambil angka terakhir dari ID halaman

    $kuesioner = Kuesioner::with(['pertanyaan' => function($query) {
        $query->with('logika'); // Ambil logika yang terkait dengan pertanyaan
    }])->findOrFail($id);

    // Ambil semua halaman yang terkait dengan pertanyaan
    $halamanIds = $kuesioner->pertanyaan->pluck('halaman_id')->unique();
    $halaman = Halaman::whereIn('id', $halamanIds)->get();

    // Siapkan data untuk JavaScript
    $existingQuestions = $kuesioner->pertanyaan->map(function($pertanyaan) {
        $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan);

        $dataLogika = $pertanyaan->logika->map(function($logika) {
            return [
                'pertanyaan_id' => $logika->pertanyaan_id, // Ambil pertanyaan_id
                'logika_id' => $logika->id, // Ambil logika
                'option_name' => json_decode($logika->data_pertanyaan)->option_name,
                'opsi_jawaban' => json_decode($logika->data_pertanyaan)->opsi_jawaban,
                'teks_pertanyaan' => json_decode($logika->data_pertanyaan)->teks_pertanyaan,
                'tipe_pertanyaan' => json_decode($logika->data_pertanyaan)->tipe_pertanyaan,
            ];
        });

        return [
            'pertanyaan_id' => $pertanyaan->id, // Ambil pertanyaan_id dari pertanyaan
            'tipe_pertanyaan' => $dataPertanyaan->tipe_pertanyaan,
            'pertanyaan' => $dataPertanyaan->teks_pertanyaan, // Mengambil teks_pertanyaan
            'opsi_jawaban' => array_map(function($opsi) {
                return $opsi->opsiJawaban; // Mengambil opsiJawaban dari opsi_jawaban
            }, array: $dataPertanyaan->opsi_jawaban),
            'nomor_pertanyaan' => $dataPertanyaan->nomor_pertanyaan,
            'is_required' => $dataPertanyaan->is_required,
            'logika' => $dataLogika, // Ambil logika dari relasi
            'halaman_id' => $pertanyaan->halaman_id, // Ambil halaman_id
        ];
    });

    return view('kuesioner.admin.edit', compact('kuesioner', 'halaman', 'existingQuestions', 'lastKuesionerId','lastQuestionId','lastLogicId','lastPageId',));
}
public function update(Request $request, $id)
{
    try {
        // Validasi data
        $request->validate([
            'judul_kuesioner' => 'required|string|max:255',
            'kuesioner_id' => 'required|string',
            'pages' => 'required|array',
            'pages.*.halaman_id' => 'required|string',
            'pages.*.judul_halaman' => 'required|string|max:255',
            'pages.*.deskripsi_halaman' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.kode_pertanyaan' => 'required|string',
            'questions.*.tipe_pertanyaan' => 'required|string',
            'questions.*.teks_pertanyaan' => 'required|string',
            'questions.*.opsi_jawaban' => 'nullable|array',
            'questions.*.halaman_id' => 'required|string',
            'logics' => 'nullable|array',
            'logics.*.id' => 'nullable|string',
            'logics.*.pertanyaan_id' => 'nullable|string',
        ]);

        // Temukan kuesioner
        $kuesioner = Kuesioner::findOrFail($id);
         // Buat slug dari judul kuesioner
        $slug = Str::slug($request->judul_kuesioner);

         // Pastikan slug unik
        $originalSlug = $slug;
        $count = 1;

        while (Kuesioner::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count; // Tambahkan angka ke slug
            $count++;
        }

        $kuesioner->update([
            'judul_kuesioner' => $request->judul_kuesioner,
            'slug' => $slug,
        ]);

        // Hapus halaman yang ada
        Halaman::where('kuesioner_id', $kuesioner->id)->delete();

        // Proses halaman baru
        foreach ($request->pages as $page) {
            Halaman::create([
                'id' => $page['halaman_id'],
                'judul_halaman' => $page['judul_halaman'],
                'deskripsi_halaman' => $page['deskripsi_halaman'],
                'kuesioner_id' => $kuesioner->id,
            ]);
        }

        // Hapus pertanyaan yang ada
        Pertanyaan::where('kuesioner_id', $kuesioner->id)->delete();

        // Proses pertanyaan baru
        foreach ($request->questions as $question) {
            $pertanyaan = Pertanyaan::create([
                'id' => $question['kode_pertanyaan'],
                'data_pertanyaan' => json_encode([
                    'tipe_pertanyaan' => $question['tipe_pertanyaan'],
                    'teks_pertanyaan' => $question['teks_pertanyaan'],
                    'opsi_jawaban' => $question['opsi_jawaban'] ?? [],
                    'nomor_pertanyaan' => $question['nomor_pertanyaan'],
                    'is_required' => $question['is_required'],
                ]),
                'halaman_id' => $question['halaman_id'],
                'kuesioner_id' => $kuesioner->id,
            ]);

            // Proses logika untuk pertanyaan ini
            foreach ($request->logics as $logic) {
                if ($logic['pertanyaan_id'] === $question['kode_pertanyaan']) {
                    Logika::create([
                        'id' => $logic['id'],
                        'data_pertanyaan' => json_encode([
                            'option_name' => $logic['option_name'],
                            'tipe_pertanyaan' => $logic['tipe_pertanyaan'],
                            'teks_pertanyaan' => $logic['teks_pertanyaan'],
                            'opsi_jawaban' => $logic['opsi_jawaban'] ?? [],
                        ]),
                        'pertanyaan_id' => $pertanyaan->id,
                        'kuesioner_id' => $kuesioner->id,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Kuesioner berhasil diperbarui.'], 200);
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

        // Cek apakah alumni sudah mengisi pertanyaan dalam kuesioner ini
        $pertanyaanIds = Pertanyaan::where('kuesioner_id', $request->kuesioner_id)->pluck('id')->toArray();
        $jawabanKuesioner = Jawaban_kuesioner::where('alumni_id', $alumniId)
            ->whereIn('pertanyaan_id', $pertanyaanIds)
            ->exists(); // Cek apakah ada jawaban untuk pertanyaan terkait

        if ($jawabanKuesioner) {
            return response()->json([
                'error' => 'Anda sudah mengisi kuesioner ini sebelumnya.'
            ], 403); // Mengembalikan status forbidden
        }

        // Proses jawaban pertanyaan
        foreach ($request->jawaban as $halamanId => $jawabanHalaman) {
            // Proses jawaban pertanyaan
            if (isset($jawabanHalaman['pertanyaan'])) {
                foreach ($jawabanHalaman['pertanyaan'] as $pertanyaanId => $jawaban) {
                    // Cek apakah $pertanyaanId valid
                    $pertanyaan = Pertanyaan::find($pertanyaanId);
                
                    if (!$pertanyaan) {
                        return response()->json([
                            'error' => "Pertanyaan ID '$pertanyaanId' tidak valid."
                        ], 422);
                    }

                    // Jika jawaban adalah array (checkbox), simpan setiap nilai
                    if (is_array($jawaban)) {
                        foreach ($jawaban as $value) {
                            Jawaban_kuesioner::create([
                                'jawaban' => $value,
                                'alumni_id' => $alumniId,
                                'pertanyaan_id' => $pertanyaan->id,
                            ]);
                        }
                    } else {
                        // Simpan jawaban tunggal
                        Jawaban_kuesioner::create([
                            'jawaban' => $jawaban,
                            'alumni_id' => $alumniId,
                            'pertanyaan_id' => $pertanyaan->id,
                        ]);
                    }
                }
            }

            // Proses jawaban logika
            if (isset($jawabanHalaman['logika'])) {
                foreach ($jawabanHalaman['logika'] as $logikaId => $logikaJawaban) {
                    // Validasi logika_id
                    $logika = Logika::find($logikaId);
            
                    if (!$logika) {
                        return response()->json([
                            'error' => "Logika ID '$logikaId' tidak valid."
                        ], 422);
                    }

                    // Jika logikaJawaban adalah array, simpan setiap nilai
                    if (is_array($logikaJawaban)) {
                        foreach ($logikaJawaban as $value) {
                            Jawaban_logika::create([
                                'logika_id' => $logika->id,
                                'alumni_id' => $alumniId,
                                'jawaban' => $value,
                            ]);
                        }
                    } else {
                        // Simpan jawaban tunggal
                        Jawaban_logika::create([
                            'logika_id' => $logika->id,
                            'alumni_id' => $alumniId,
                            'jawaban' => $logikaJawaban,
                        ]);
                    }
                }
            }
        }

        return response()->json(['message' => 'Jawaban berhasil disimpan!']);
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

public function thankYouPage()
{
    return view('kuesioner.alumni.finish');
}

    
    public function createStatus()
{
    // Ambil semua pertanyaan yang mengandung kata "bekerja" atau "status"
    $pertanyaans = Pertanyaan::where(function($query) {
        $query->where('data_pertanyaan', 'LIKE', '%bekerja%')
              ->orWhere('data_pertanyaan', 'LIKE', '%status%')
              ->orWhere('data_pertanyaan', 'LIKE', '%pekerjaan%');
    })->get()->map(function ($pertanyaan) {
        // Mengambil id dan mengonversi data_pertanyaan dari JSON string menjadi array
        $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan, true);
        
        // Ambil logika yang terkait dengan pertanyaan
        $logika = $pertanyaan->logika;

        // Siapkan array untuk menyimpan data logika
        $logikaData = [];
        foreach ($logika as $item) {
            $dataLogika = json_decode($item->data_pertanyaan, true);
            $logikaData[] = [
                'id' => $item->id,
                'teks_logika' => $dataLogika['teks_pertanyaan'] ?? 'Tidak ada teks logika',
                'opsi_jawaban_logika' => $dataLogika['opsi_jawaban'] ?? 'Tidak ada opsi jawaban',
            ];
        }

        return [
            'id' => $pertanyaan->id,
            'teks_pertanyaan' => $dataPertanyaan['teks_pertanyaan'] ?? 'Tidak ada teks pertanyaan',
            'opsi_jawaban' => $dataPertanyaan['opsi_jawaban'] ?? 'Tidak ada opsi jawaban',
            'logika' => $logikaData,
        ];
    });

    // Ambil semua logika yang terkait dengan pertanyaan yang diambil
    $semuaLogika = Logika::where(function($query) {
        $query->where('data_pertanyaan', 'LIKE', '%bekerja%')
              ->orWhere('data_pertanyaan', 'LIKE', '%status%')
              ->orWhere('data_pertanyaan', 'LIKE', '%pekerjaan%');

    })->get()->map(function ($item) {
        $dataLogika = json_decode($item->data_pertanyaan, true);
        return [
            'id' => $item->id,
            'teks_logika' => $dataLogika['teks_pertanyaan'] ?? 'Tidak ada teks logika',
            'opsi_jawaban_logika' => $dataLogika['opsi_jawaban'] ?? 'Tidak ada opsi jawaban',
        ];
    });

    // Panggil fungsi untuk mendapatkan data chart
    $charts = $this->showStatusChart();

    return view('admin.chart.createStatus', compact('pertanyaans', 'semuaLogika', 'charts'));
}

public function storeStatusData(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'questionOrLogikaId' => 'required|string',
        'opsiJawaban' => 'array|nullable',
        'type' => 'required|string', // Validasi type
    ]);

    // Siapkan data untuk disimpan
    $dataStatus = [
        'id' => $validatedData['questionOrLogikaId'],
        'type' => $validatedData['type'], // Menyimpan type
        'opsi_jawaban' => $validatedData['opsiJawaban'] ?? [],
    ];

    // Cek apakah data sudah ada
    $existingData = Status::where('data_status->id', $validatedData['questionOrLogikaId'])
                                ->where('data_status->type', $validatedData['type'])
                                ->first();

    if ($existingData) {
        // Jika data sudah ada, perbarui data yang ada
        $existingData->update([
            'data_status' => json_encode($dataStatus), // Perbarui data dalam format JSON
        ]);
    } else {
        // Jika data belum ada, simpan data baru
        Status::create([
            'data_status' => json_encode($dataStatus), // Simpan data dalam format JSON
        ]);
    }

    // Redirect ke halaman dengan ID kuesioner
    return redirect()->route('admin.status.create')
                     ->with('success', 'Data chart berhasil disimpan!');
}

public function showStatusChart()
{
    // Ambil semua data status
    $statusRecords = Status::all();

    // Siapkan data untuk chart
    $charts = [];

    // Inisialisasi data untuk status bekerja
    $dataChart = [
        'labels' => ['Sudah Bekerja', 'Belum Bekerja'],
        'data' => [0, 0] // Indeks 0 untuk 'Sudah Bekerja', indeks 1 untuk 'Belum Bekerja'
    ];

    foreach ($statusRecords as $record) {
        $dataStatus = json_decode($record->data_status, true);
        
        // Ambil opsi jawaban
        $opsiJawaban = $dataStatus['opsi_jawaban'];
        $type = $dataStatus['type']; // Ambil type dari data_status

        if ($type === 'pertanyaan') {
            // Hitung jumlah untuk 'Sudah Bekerja' dan 'Belum Bekerja' dari jawaban_kuesioner
            if (!empty($opsiJawaban)) {
                foreach ($opsiJawaban as $opsi) {
                    // Ambil jawaban dari tabel jawaban_kuesioner
                    $jawabanKuesioner = Jawaban_kuesioner::where('pertanyaan_id', $dataStatus['id'])->get();
                    $jawabanCount = $jawabanKuesioner->where('jawaban', $opsi)->count();

                    // Cek apakah opsi adalah 'sudah bekerja' atau 'belum bekerja'
                    if (strtolower($opsi) === 'sudah bekerja') {
                        $dataChart['data'][0] += $jawabanCount; // Tambah jumlah untuk 'Sudah Bekerja'
                    } elseif (strtolower($opsi) === 'belum bekerja') {
                        $dataChart['data'][1] += $jawabanCount; // Tambah jumlah untuk 'Belum Bekerja'
                    }
                }
            }
        } elseif ($type === 'logika') {
            // Hitung jumlah untuk 'Sudah Bekerja' dan 'Belum Bekerja' dari jawaban_logika
            if (!empty($opsiJawaban)) {
                foreach ($opsiJawaban as $opsi) {
                    // Ambil jawaban dari tabel jawaban_logika
                    $jawabanLogika = Jawaban_logika::where('logika_id', $dataStatus['id'])->get();
                    $jawabanCount = $jawabanLogika->where('jawaban', $opsi)->count();

                    // Cek apakah opsi adalah 'sudah bekerja' atau 'belum bekerja'
                    if (strtolower($opsi) === 'sudah bekerja') {
                        $dataChart['data'][0] += $jawabanCount; // Tambah jumlah untuk 'Sudah Bekerja'
                    } elseif (strtolower($opsi) === 'belum bekerja') {
                        $dataChart['data'][1] += $jawabanCount; // Tambah jumlah untuk 'Belum Bekerja'
                    }
                }
            }
        }
    }

    // Siapkan chart data
    $charts[] = [
        'data' => $dataChart,
        'chartType' => 'bar', // Atau jenis chart yang Anda inginkan
        'title' => 'Status Bekerja Alumni',
    ];

    return $charts;
}

public function getStatusCounts()
{
    // Ambil semua data status
    $statusRecords = Status::all();

    // Inisialisasi jumlah untuk status bekerja
    $counts = [
        'sudah_bekerja' => 0,
        'belum_bekerja' => 0,
    ];

    foreach ($statusRecords as $record) {
        $dataStatus = json_decode($record->data_status, true);
        
        // Ambil opsi jawaban
        $opsiJawaban = $dataStatus['opsi_jawaban'];
        $type = $dataStatus['type']; // Ambil type dari data_status

        if (!empty($opsiJawaban)) {
            if ($type === 'pertanyaan') {
                // Hitung jumlah untuk 'Sudah Bekerja' dan 'Belum Bekerja' dari jawaban_kuesioner
                foreach ($opsiJawaban as $opsi) {
                    if (strtolower($opsi) === 'sudah bekerja') {
                        $counts['sudah_bekerja']++; // Tambah jumlah untuk 'Sudah Bekerja'
                    } elseif (strtolower($opsi) === 'belum bekerja') {
                        $counts['belum_bekerja']++; // Tambah jumlah untuk 'Belum Bekerja'
                    }
                }
            } elseif ($type === 'logika') {
                // Hitung jumlah untuk 'Sudah Bekerja' dan 'Belum Bekerja' dari jawaban_logika
                foreach ($opsiJawaban as $opsi) {
                    if (strtolower($opsi) === 'sudah bekerja') {
                        $counts['sudah_bekerja']++; // Tambah jumlah untuk 'Sudah Bekerja'
                    } elseif (strtolower($opsi) === 'belum bekerja') {
                        $counts['belum_bekerja']++; // Tambah jumlah untuk 'Belum Bekerja'
                    }
                }
            }
        }
    }

    // Kembalikan jumlah alumni yang sudah dan belum bekerja
    return $counts;
}

public function chartIndex()
    {
        // Ambil semua kuesioner dari database
        $kuesioners = Kuesioner::all();

        // Kembalikan view dengan data kuesioner
        return view('admin.chart.index', compact('kuesioners'));
    }


    public function createChart($kuesionerId)
{
    // Ambil semua pertanyaan yang berhubungan dengan kuesioner_id tertentu
    $pertanyaans = Pertanyaan::where('kuesioner_id', $kuesionerId)->get()->map(function ($pertanyaan) {
        // Mengambil id dan mengonversi data_pertanyaan dari JSON string menjadi array
        $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan, true);
        
        // Ambil logika yang terkait dengan pertanyaan
        $logika = $pertanyaan->logika;

        // Siapkan array untuk menyimpan data logika
        $logikaData = [];
        foreach ($logika as $item) {
            $dataLogika = json_decode($item->data_pertanyaan, true);
            $logikaData[] = [
                'id' => $item->id,
                'teks_logika' => $dataLogika['teks_pertanyaan'] ?? 'Tidak ada teks logika',
                'opsi_jawaban_logika' => $dataLogika['opsi_jawaban'] ?? 'Tidak ada opsi jawaban',
            ];
        }

        return [
            'id' => $pertanyaan->id,
            'teks_pertanyaan' => $dataPertanyaan['teks_pertanyaan'] ?? 'Tidak ada teks pertanyaan',
            'opsi_jawaban' => $dataPertanyaan['opsi_jawaban'] ?? 'Tidak ada opsi jawaban',
            'logika' => $logikaData,
        ];
    });

    // Ambil semua logika yang terkait dengan pertanyaan yang diambil
    $semuaLogika = Logika::whereIn('pertanyaan_id', $pertanyaans->pluck('id'))->get()->map(function ($item) {
        $dataLogika = json_decode($item->data_pertanyaan, true);
        return [
            'id' => $item->id,
            'teks_logika' => $dataLogika['teks_pertanyaan'] ?? 'Tidak ada teks logika',
            'opsi_jawaban_logika' => $dataLogika['opsi_jawaban'] ?? 'Tidak ada opsi jawaban',
        ];
    });

    return view('admin.chart.create', compact('pertanyaans', 'semuaLogika', 'kuesionerId'));
}

    public function storeChartData(Request $request)
    {
    // Validasi input
    $validatedData = $request->validate([
        'questionOrLogikaId' => 'required|string',
        'opsiJawaban' => 'array|nullable',
        'kuesioner_id' => 'required|string',
        'type' => 'required|string', // Validasi type
        'chartType' => 'required|string',
        'judulChart' => 'required|string'
    ]);

    // Siapkan data untuk disimpan
    $dataChart = [
        'id' => $validatedData['questionOrLogikaId'],
        'judul_chart' => $validatedData['judulChart'], // Menyimpan type
        'type' => $validatedData['type'], // Menyimpan type
        'chart_type' => $validatedData['chartType'], // Menyimpan type
        'opsi_jawaban' => $validatedData['opsiJawaban'] ?? [],
    ];

    // Simpan data chart ke dalam database
    Chart_data::create([
        'data_chart' => json_encode($dataChart), // Simpan data dalam format JSON
        'kuesioner_id' => $validatedData['kuesioner_id']
    ]);

     // Redirect ke halaman dengan ID kuesioner
     return redirect()->route('admin.chart.show', ['kuesionerId' => $validatedData['kuesioner_id']])
     ->with('success', 'Data chart berhasil disimpan!');
    }

public function showChart($kuesionerId)
{
    // Ambil kuesioner berdasarkan ID
    $kuesioner = Kuesioner::findOrFail($kuesionerId);

    // Ambil semua data chart berdasarkan kuesioner_id
    $chartDataRecords = Chart_data::where('kuesioner_id', $kuesionerId)->get();

    // Siapkan data untuk chart
    $charts = [];

    foreach ($chartDataRecords as $record) {
        $dataChart = json_decode($record->data_chart, true);
        
        // Ambil type dari data charta
        $judulChart = $dataChart['judul_chart'];
        $type = $dataChart['type'];
        $chartType = $dataChart['chart_type'];

        // Siapkan data untuk setiap chart
        $chartData = [
            'labels' => [],
            'data' => []
        ];

        if ($type === 'pertanyaan') {
            // Ambil jawaban dari tabel jawaban_kuesioner
            $jawabanKuesioner = Jawaban_kuesioner::where('pertanyaan_id', $dataChart['id'])->get();
            $opsiJawaban = $dataChart['opsi_jawaban'];

            if (!empty($opsiJawaban)) {
                foreach ($opsiJawaban as $opsi) {
                    $jawabanCount = $jawabanKuesioner->where('jawaban', $opsi)->count();
                    // Cek apakah opsi sudah ada di labels
                    $index = array_search($opsi, $chartData['labels']);
                    if ($index !== false) {
                        // Jika sudah ada, tambahkan jumlahnya
                        $chartData['data'][$index] += $jawabanCount;
                    } else {
                        // Jika belum ada, tambahkan ke labels dan data
                        $chartData['labels'][] = $opsi;
                        $chartData['data'][] = $jawabanCount;
                    }
                }
            } else {
                foreach ($jawabanKuesioner as $jawaban) {
                    // Cek apakah jawaban sudah ada di labels
                    $index = array_search($jawaban->jawaban, $chartData['labels']);
                    if ($index !== false) {
                        // Jika sudah ada, tambahkan jumlahnya
                        $chartData['data'][$index] += 1;
                    } else {
                        // Jika belum ada, tambahkan ke labels dan data
                        $chartData['labels'][] = $jawaban->jawaban;
                        $chartData['data'][] = 1;
                    }
                }
            }
        } elseif ($type === 'logika') {
            // Ambil jawaban dari tabel jawaban_logika
            $jawabanLogika = Jawaban_logika::where('logika_id', $dataChart['id'])->get();
            $opsiJawaban = $dataChart['opsi_jawaban'];

            if (!empty($opsiJawaban)) {
                foreach ($opsiJawaban as $opsi) {
                    $jawabanCount = $jawabanLogika->where('jawaban', $opsi)->count();
                    // Cek apakah opsi sudah ada di labels
                    $index = array_search($opsi, $chartData['labels']);
                    if ($index !== false) {
                        // Jika sudah ada, tambahkan jumlahnya
                        $chartData['data'][$index] += $jawabanCount;
                    } else {
                        // Jika belum ada, tambahkan ke labels dan data
                        $chartData['labels'][] = $opsi;
                        $chartData['data'][] = $jawabanCount;
                    }
                }
            } else {
                foreach ($jawabanLogika as $jawaban) {
                    // Cek apakah jawaban sudah ada di labels
                    $index = array_search($jawaban->jawaban, $chartData['labels']);
                    if ($index !== false) {
                        // Jika sudah ada, tambahkan jumlahnya
                        $chartData['data'][$index] += 1;
                    } else {
                        // Jika belum ada, tambahkan ke labels dan data
                        $chartData['labels'][] = $jawaban->jawaban;
                        $chartData['data'][] = 1;
                    }
                }
            }
        }

        $charts[] = [
            'id' => $record->id,
            'data' => $chartData,
            'chartType' => $chartType,
            'title' => $judulChart,
        ];
    }

    return view('admin.chart.show', compact('kuesioner', 'charts'));
}

public function deleteChart($chartId)
{
    // Cari chart berdasarkan ID
    $chart = Chart_data::find($chartId);

    // Cek apakah chart ditemukan
    if ($chart) {
        // Hapus chart dari database
        $chart->delete();

        // Kembalikan respons JSON
        return response()->json(['success' => 'Chart berhasil dihapus!']);
    } else {
        // Jika chart tidak ditemukan
        return response()->json(['error' => 'Chart tidak ditemukan!'], 404);
    }
}

}


