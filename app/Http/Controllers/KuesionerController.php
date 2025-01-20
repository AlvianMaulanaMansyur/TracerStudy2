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
        $lastQuestionId = $lastQuestion ? (int) substr($lastQuestion->id, 3) : 0; // Ambil angka terakhir dari ID pertanyaan
    
        // Ambil ID terakhir dari tabel logika
        $lastLogic = DB::table('logika')->orderBy('id', 'desc')->first();
        $lastLogicId = $lastLogic ? (int) substr($lastLogic->id, 2) : 0; // Ambil angka terakhir dari ID logika
    
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
    // Ambil ID terakhir dari tabel pertanyaan
    $lastKuesioner = DB::table('kuesioner')->orderBy('id', 'desc')->first();
    $lastKuesionerId = $lastKuesioner ? (int) substr($lastKuesioner->id, 1) : 0; // Ambil angka terakhir dari ID pertanyaan

    // Ambil ID terakhir dari tabel pertanyaan
    $lastQuestion = DB::table('pertanyaan')->orderBy('id', 'desc')->first();
    $lastQuestionId = $lastQuestion ? (int) substr($lastQuestion->id, 3) : 0; // Ambil angka terakhir dari ID pertanyaan

    // Ambil ID terakhir dari tabel logika
    $lastLogic = DB::table('logika')->orderBy('id', 'desc')->first();
    $lastLogicId = $lastLogic ? (int) substr($lastLogic->id, 2) : 0; // Ambil angka terakhir dari ID logika

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
            }, $dataPertanyaan->opsi_jawaban),
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

        // Proses halaman
        foreach ($request->pages as $page) {
            Halaman::updateOrCreate(
                ['id' => $page['halaman_id']],
                [
                    'judul_halaman' => $page['judul_halaman'],
                    'deskripsi_halaman' => $page['deskripsi_halaman'],
                ]
            );
        }

        // Proses pertanyaan
        $savedQuestionIds = [];
        foreach ($request->questions as $question) {
            $pertanyaan = Pertanyaan::updateOrCreate(
                ['id' => $question['kode_pertanyaan']],
                [
                    'data_pertanyaan' => json_encode([
                        'tipe_pertanyaan' => $question['tipe_pertanyaan'],
                        'teks_pertanyaan' => $question['teks_pertanyaan'],
                        'opsi_jawaban' => $question['opsi_jawaban'] ?? [],
                    ]),
                    'halaman_id' => $question['halaman_id'],
                    'kuesioner_id' => $kuesioner->id,
                ]
            );

            $savedQuestionIds[] = $pertanyaan->id;

            // Proses logika untuk pertanyaan ini
            foreach ($request->logics as $logic) {
                if ($logic['pertanyaan_id'] === $question['kode_pertanyaan']) {
                    Logika::updateOrCreate(
                        ['id' => $logic['id'] ?? Str::uuid()->toString()],
                        [
                            'data_pertanyaan' => json_encode([
                                'option_name' => $logic['option_name'],
                                'tipe_pertanyaan' => $logic['tipe_pertanyaan'],
                                'teks_pertanyaan' => $logic['teks_pertanyaan'],
                                'opsi_jawaban' => $logic['opsi_jawaban'] ?? [],
                            ]),
                            'pertanyaan_id' => $pertanyaan->id,
                        ]
                    );
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
    
                        // Simpan jawaban
                        Jawaban_kuesioner::create([
                            'jawaban' => $jawaban,
                            'alumni_id' => $alumniId,
                            'pertanyaan_id' => $pertanyaan->id,
                        ]);
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
                
                        Jawaban_logika::create([
                            'logika_id' => $logika->id,
                            'alumni_id' => $alumniId,
                            'jawaban' => $logikaJawaban,
                        ]);
                    }
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

public function indexAlumniChart()
{
    // Ambil data alumni jika diperlukan
    $alumni = Alumni::all(); // Atau sesuaikan dengan kebutuhan Anda

    return view('admin.chart.indexAlumni');
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
