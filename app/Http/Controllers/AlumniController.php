<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Chart_data;
use App\Models\Jawaban_kuesioner;
use App\Models\Jawaban_logika;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AlumniController extends Controller
{
    public function dashboard()
    {
        return view('alumni.dashboard');
    }
    //

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login or home page
        return redirect('/login');
    }

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

        $datastatusalumni = $this->getstatusalumni();

        return view('alumni.home', compact('jumlahAlumni', 'totalAlumni', 'angkatanPerJurusan', 'jumlahMahasiswaAktif', 'totalMahasiswaAktif', 'angkatanPerTahunLulus', 'angkatanPerTahun', 'datastatusalumni')); // Mengembalikan tampilan home alumni
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

        $charts = $this->getAllCharts();
        $datastatusalumni = $this->getstatusalumni();

        return view('alumni.statistik', compact('jumlahAlumni', 'totalAlumni', 'angkatanPerJurusan', 'jumlahMahasiswaAktif', 'totalMahasiswaAktif', 'angkatanPerTahunLulus', 'angkatanPerTahun', 'charts', 'datastatusalumni')); // Mengembalikan tampilan home alumni
    }

    public function getstatusalumni()
    {

        // Ambil semua data status
        $statusRecords = Status::all();

        // Inisialisasi jumlah untuk status bekerja
        $counts = [
            'jumlah_sudah' => 0,
            'jumlah_belum' => 0,
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
                        // Ambil jawaban dari tabel jawaban_logika
                        $jawabanKuesioner = Jawaban_kuesioner::where('pertanyaan_id', $dataStatus['id'])->get();
                        $jawabanCount = $jawabanKuesioner->where('jawaban', $opsi)->count();

                        if (strtolower($opsi) === 'sudah bekerja') {
                            $counts['jumlah_sudah']+= $jawabanCount; // Tambah jumlah untuk 'Sudah Bekerja'
                        } elseif (strtolower($opsi) === 'belum bekerja') {
                            $counts['jumlah_belum']+= $jawabanCount; // Tambah jumlah untuk 'Belum Bekerja'
                        }
                    }
                } elseif ($type === 'logika') {
                    // Hitung jumlah untuk 'Sudah Bekerja' dan 'Belum Bekerja' dari jawaban_logika
                    foreach ($opsiJawaban as $opsi) {
                        // Ambil jawaban dari tabel jawaban_logika
                        $jawabanLogika = Jawaban_logika::where('logika_id', $dataStatus['id'])->get();
                        $jawabanCount = $jawabanLogika->where('jawaban', $opsi)->count();

                        if (strtolower($opsi) === 'sudah bekerja') {
                            $counts['jumlah_sudah']+= $jawabanCount; // Tambah jumlah untuk 'Sudah Bekerja'
                        } elseif (strtolower($opsi) === 'belum bekerja') {
                            $counts['jumlah_belum']+= $jawabanCount; // Tambah jumlah untuk 'Belum Bekerja'
                        }
                    }
                }
            }
        }

        // Kembalikan jumlah alumni yang sudah dan belum bekerja
        return $counts;
    }


    public function getAllCharts()
    {
        // Ambil semua data chart
        $chartDataRecords = Chart_data::all();

        // Siapkan data untuk chart
        $charts = [];

        foreach ($chartDataRecords as $record) {
            $dataChart = json_decode($record->data_chart, true);

            // Ambil type dari data chart
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
            $kuesionerId = $record->kuesioner_id; // Pastikan ada kolom kuesioner_id di Chart_data
            $charts[$kuesionerId][] = [
                'id' => $record->id,
                'data' => $chartData,
                'chartType' => $chartType,
                'title' => $judulChart,
            ];
        }

        return $charts;
    }

    public function faq()
    {
        // Anda bisa mengambil data alumni dari database jika diperlukan
        // $alumni = Alumni::all(); // Contoh pengambilan data alumni

        return view('alumni.faq'); // Mengembalikan tampilan home alumni
    }
}
