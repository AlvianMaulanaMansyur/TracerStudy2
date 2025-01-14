<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;

class SyncAlumniData extends Command
{
    protected $signature = 'sync:alumni';
    protected $description = 'Sinkronisasi data alumni dari API';

    public function handle()
    {
        // Parameter yang diberikan
        $tahunAkademikList = ["20221", "20222"];
        $jurusan = ""; // Tetap kosong untuk API, filter dilakukan di Laravel
        $prodi = "";
        $hashKey = "P0l1t3kn1k&N3g3r1%B4l1";

        foreach ($tahunAkademikList as $tahunAkademik) {
            // Gabungkan parameter
            $concatenatedString = $tahunAkademik . $jurusan . $prodi . $hashKey;
            $hashCode = strtoupper(hash('sha256', $concatenatedString));

            // Lakukan request ke API
            $url = 'https://webapi.pnb.ac.id/api/mahasiswa';
            $response = Http::post($url, [
                'tahunAkademik' => $tahunAkademik,
                'jurusan' => $jurusan,
                'prodi' => $prodi,
                'hashCode' => $hashCode,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['daftar']) && is_array($data['daftar'])) {
                    foreach ($data['daftar'] as $item) {
                        // Sinkronisasi Jurusan
                        $jurusan = Jurusan::where('nama_jurusan', $item['jurusan'])->first();

                        if (!$jurusan) {
                            // Jika jurusan tidak ada, buat baru
                            $jurusan = Jurusan::create([
                                'id' => $item['kodeJurusan'],
                                'nama_jurusan' => $item['jurusan']
                            ]);
                        } else {
                            // Jika jurusan sudah ada, perbarui jika nama berbeda
                            if ($jurusan->nama_jurusan !== $item['jurusan']) {
                                $jurusan->update(['nama_jurusan' => $item['jurusan']]);
                            }
                        }

                        // Sinkronisasi Prodi
                        $prodi = Prodi::where('nama_prodi', $item['prodi'])->where('jurusan_id', $jurusan->id)->first();

                        if (!$prodi) {
                            // Jika prodi tidak ada, buat baru
                            $prodi = Prodi::create([
                                'id' => $item['kodeProdi'],
                                'nama_prodi' => $item['prodi'],
                                'jurusan_id' => $jurusan->id
                            ]);
                        } else {
                            // Jika prodi sudah ada, perbarui jika nama berbeda
                            if ($prodi->nama_prodi !== $item['prodi']) {
                                $prodi->update(['nama_prodi' => $item['prodi']]);
                            }
                        }

                        // Filter hanya jurusan Teknologi Informasi
                        if ($item['jurusan'] === 'Teknologi Informasi') {
                            $nim = $item['nim'];
                            $angkatan = '20' . substr($nim, 0, 2); // Mengambil tahun angkatan dari NIM

                            // Menentukan tahun lulus berdasarkan jenjang
                            $tahunLulus = $this->hitungTahunLulus($angkatan, $item['jenjang']);

                            // Sinkronisasi Alumni
                            Alumni::updateOrCreate(
                                ['nim' => $nim],
                                [
                                    'nama_alumni' => $item['nama'],
                                    'email' => $item['email'],
                                    'no_telepon' => $item['telepon'],
                                    'angkatan' => $angkatan, // Gunakan tahun angkatan yang diekstrak
                                    'prodi_id' => $prodi->id,
                                    'jenjang' => $item['jenjang'] ?? 'Tidak Diketahui',
                                    'nik' => 0,
                                    'password' => Hash::make('password'),
                                    'jenis_kelamin' => 'N/A',
                                    'tahun_lulus' => $tahunLulus, // Gunakan tahun lulus yang dihitung
                                    'gelombang_wisuda' => 1,
                                    'status_verifikasi' => 1
                                ]
                            );
                        }
                    }
                    $this->info('Data alumni jurusan Teknologi Informasi berhasil disinkronkan.');
                } else {
                    $this->error('Data daftar tidak ditemukan di API.');
                }
            } else {
                $this->error('Gagal mengakses API.');
                $this->error('Status: ' . $response->status());
                $this->error('Response: ' . $response->body());
            }
        }
    }

    private function hitungTahunLulus($angkatan, $jenjang)
    {
        $durasi = 0;
        switch ($jenjang) {
            case 'D2':
                $durasi = 2;
                break;
            case 'D3':
                $durasi = 3;
                break;
            case 'D4':
                $durasi = 4;
                break;
            default:
                $durasi = 0; // Jika jenjang tidak dikenali
                break;
        }
        return (int)$angkatan + $durasi; // Menghitung tahun lulus
    }
}