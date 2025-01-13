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
        $kuesioner = Kuesioner::find($kuesionerId);
    
        if (!$kuesioner) {
            abort(404, 'Kuesioner tidak ditemukan');
        }
    
        // Query untuk alumni yang sudah menjawab
        $sudah = Alumni::with('prodi')
            ->whereHas('jawaban_kuesioner', function ($query) use ($kuesionerId) {
                $query->whereHas('pertanyaan', function ($subQuery) use ($kuesionerId) {
                    $subQuery->where('kuesioner_id', $kuesionerId);
                });
            })
            ->select('id', 'nim', 'nama_alumni', 'prodi_id')
            ->get()
            ->map(function ($alumni) {
                return [
                    'nim' => $alumni->nim,
                    'nama' => $alumni->nama_alumni,
                    'prodi' => $alumni->prodi ? $alumni->prodi->nama : '-',
                    'status' => 'sudah',
                ];
            });
    
        // Query untuk alumni yang belum menjawab
        $belum = Alumni::with('prodi')
            ->whereDoesntHave('jawaban_kuesioner', function ($query) use ($kuesionerId) {
                $query->whereHas('pertanyaan', function ($subQuery) use ($kuesionerId) {
                    $subQuery->where('kuesioner_id', $kuesionerId);
                });
            })
            ->select('id', 'nim', 'nama_alumni', 'prodi_id')
            ->get()
            ->map(function ($alumni) {
                return [
                    'nim' => $alumni->nim,
                    'nama' => $alumni->nama_alumni,
                    'prodi' => $alumni->prodi ? $alumni->prodi->nama : '-',
                    'status' => 'belum',
                ];
            });
    
        // Gabungkan dan buat pagination manual
        $combined = $sudah->merge($belum);
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
        return view('kuesioner.admin.rekap', compact('kuesioners', 'paginated', 'kuesionerId'));
    }    
}
