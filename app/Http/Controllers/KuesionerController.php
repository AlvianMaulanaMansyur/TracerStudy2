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
                // 'judul_kuesioner' => 'required|string|max:255',
                // 'questions.*.teks_pertanyaan' => 'required|string',
                // 'questions.*.tipe_pertanyaan' => 'required|string',
                // 'questions.*.opsi_jawaban.*' => 'nullable|string',
                // 'questions.*.logika' => 'nullable|array', // Validasi logika jika ada
                // 'questions.*.logika.*.opsi' => 'required|string', // Validasi opsi dalam logika
                // 'questions.*.logika.*.halaman' => 'required|string', // Validasi halaman dalam logika
            ]);
    
            // Simpan data kuesioner ke database
            $kuesioner = Kuesioner::create([
                'admin_id' => 1, // Ganti dengan ID admin yang sesuai
                'judul_kuesioner' => $request->judul_kuesioner,
            ]);
    
            // Simpan pertanyaan yang terkait dengan kuesioner
            foreach ($request->questions as $question) {
                $data = [
                    'pertanyaan' => $question['teks_pertanyaan'],
                    'tipe_pertanyaan' => $question['tipe_pertanyaan'],
                    'opsi_jawaban' => $question['opsi_jawaban'],
                    'halaman' => $question['halaman'],
                    'logika' => $question['logika'] ?? null, // Menyimpan logika jika ada
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
    public function show($id, Request $request)
{
    $kuesioner = Kuesioner::findOrFail($id);
    $currentPage = $request->input('page', 1);

    // Ambil semua pertanyaan yang terkait dengan kuesioner ini
    $questions = $kuesioner->pertanyaan()->get();

    // Hitung nilai maksimum halaman dari data pertanyaan
    $maxPage = $questions->max(function ($question) {
        $data = json_decode($question->data_pertanyaan, true);
        return $data['halaman'] ?? 0; // Mengambil nilai halaman, default 0 jika tidak ada
    });

    // Set totalPages berdasarkan nilai maksimum halaman
    $totalPages = $maxPage;

    // Filter pertanyaan untuk halaman saat ini
    $filteredQuestions = $questions->filter(function ($question) use ($currentPage) {
        $data = json_decode($question->data_pertanyaan, true);
        return isset($data['halaman']) && $data['halaman'] == $currentPage;
    });

   // Simpan riwayat halaman dalam array
   $history = session('history', []);
   if (!in_array($currentPage, $history)) {
       $history[] = $currentPage;
   }
   session(['history' => $history]);

   // Hitung halaman sebelumnya
   $previousPage = null;
   if (count($history) > 1) {
       $previousPage = $history[count($history) - 2]; // Ambil halaman sebelumnya
   }

    // Render view
    return view('kuesioner.admin.show', compact('kuesioner', 'filteredQuestions', 'currentPage', 'totalPages', 'previousPage'));
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
                    'halaman'=> $question['halaman'],
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

    public function submit(Request $request, $id)
    {
        $request->validate([
            'jawaban' => 'required',
            'jawaban.*' => 'required',
        ]);
    
        // Ambil id alumni dari session atau input (sesuaikan dengan cara Anda menyimpan id)
        $alumniId = Auth::guard('alumni')->user()->id; // Misalnya, jika Anda menggunakan autentikasi
    
        // Simpan jawaban ke dalam database
        foreach ($request->jawaban as $pertanyaanId => $jawaban) {
            // Jika jawaban adalah array (misalnya untuk checkbox), simpan setiap jawaban
            if (is_array($jawaban)) {
                foreach ($jawaban as $jawabanItem) {
                    Jawaban_kuesioner::create([
                        'jawaban' => $jawabanItem,
                        'alumni_id' => $alumniId,
                        'pertanyaan_id' => $pertanyaanId,
                    ]);
                }
            } else {
                Jawaban_kuesioner::create([
                    'jawaban' => $jawaban,
                    'alumni_id' => $alumniId,
                    'pertanyaan_id' => $pertanyaanId,
                ]);
            }
        }
    
        // Redirect atau memberikan respon setelah penyimpanan berhasil
        return redirect()->route('kuesioner.alumni.show', $id)->with('success', 'Jawaban berhasil disimpan!');
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
