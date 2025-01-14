<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kuesioner;
use App\Models\Alumni;
use App\Models\Jawaban_kuesioner;

class RekapController extends Controller
{
    public function index()
    {
        $kuesioners = Kuesioner::all();
        return view('kuesioner.admin.rekap', compact('kuesioners'));
    }

    public function show(Request $request)
    {
        $kuesionerId = $request->query('kuesioner_id');
        $prodiFilter = $request->query('prodi', null); // Filter Prodi
        $kuesioner = Kuesioner::find($kuesionerId);

        if (!$kuesionerId) {
            abort(400, 'Judul kuesioner harus dipilih.');
        }
        if (!$kuesioner) {
            abort(404, 'Kuesioner tidak ditemukan');
        }

        // Periksa apakah ada alumni yang sudah menjawab
        $hasAnswers = Alumni::whereHas('jawaban_kuesioner', function ($query) use ($kuesionerId) {
            $query->whereHas('pertanyaan', function ($subQuery) use ($kuesionerId) {
                $subQuery->where('kuesioner_id', $kuesionerId);
            });
        })->exists();

        if ($hasAnswers) {
            // Query untuk alumni yang sudah menjawab
            $sudah = Alumni::with('prodi')
                ->whereHas('jawaban_kuesioner', function ($query) use ($kuesionerId) {
                    $query->whereHas('pertanyaan', function ($subQuery) use ($kuesionerId) {
                        $subQuery->where('kuesioner_id', $kuesionerId);
                    });
                });

            // Query untuk alumni yang belum menjawab
            $belum = Alumni::with('prodi')
                ->whereDoesntHave('jawaban_kuesioner', function ($query) use ($kuesionerId) {
                    $query->whereHas('pertanyaan', function ($subQuery) use ($kuesionerId) {
                        $subQuery->where('kuesioner_id', $kuesionerId);
                    });
                });

            // Terapkan filter Prodi jika ada
            if ($prodiFilter) {
                $sudah->whereHas('prodi', function ($query) use ($prodiFilter) {
                    $query->where('nama_prodi', $prodiFilter);
                });

                $belum->whereHas('prodi', function ($query) use ($prodiFilter) {
                    $query->where('nama_prodi', $prodiFilter);
                });
            }

            $sudah = $sudah->select('id', 'nim', 'nama_alumni', 'prodi_id')->get()->map(function ($alumni) {
                return [
                    'nim' => $alumni->nim,
                    'nama' => $alumni->nama_alumni,
                    'prodi' => $alumni->prodi ? $alumni->prodi->nama_prodi : '-',
                    'status' => 'sudah',
                ];
            });

            $belum = $belum->select('id', 'nim', 'nama_alumni', 'prodi_id')->get()->map(function ($alumni) {
                return [
                    'nim' => $alumni->nim,
                    'nama' => $alumni->nama_alumni,
                    'prodi' => $alumni->prodi ? $alumni->prodi->nama_prodi : '-',
                    'status' => 'belum',
                ];
            });

            // Gabungkan data alumni sudah dan belum menjawab
            $combined = $sudah->merge($belum);
        } else {
            // Jika tidak ada yang menjawab, tampilkan seluruh alumni dengan status "belum"
            $combined = Alumni::with('prodi')
                ->select('id', 'nim', 'nama_alumni', 'prodi_id');

            // Terapkan filter Prodi jika ada
            if ($prodiFilter) {
                $combined->whereHas('prodi', function ($query) use ($prodiFilter) {
                    $query->where('nama_prodi', $prodiFilter);
                });
            }

            $combined = $combined->get()->map(function ($alumni) {
                return [
                    'nim' => $alumni->nim,
                    'nama' => $alumni->nama_alumni,
                    'prodi' => $alumni->prodi ? $alumni->prodi->nama_prodi : '-',
                    'status' => 'belum',
                ];
            });
        }

        // Pagination manual
        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $combined->forPage($currentPage, $perPage),
            $combined->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return response()->json([
                'data' => $paginated->items(),
                'pagination' => (string) $paginated->links('pagination.custom'),
            ]);
        }

        $kuesioners = Kuesioner::all();

        // dd($prodiFilter);
        return view('kuesioner.admin.rekap', compact('kuesioners', 'paginated', 'kuesionerId', 'prodiFilter'));
    }
}
