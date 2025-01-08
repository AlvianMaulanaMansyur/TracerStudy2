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
        $tahunAkademik = "20222";
        $jurusan = ""; // Tetap kosong untuk API, filter dilakukan di Laravel
        $prodi = "";
        $hashKey = "P0l1t3kn1k&N3g3r1%B4l1";

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
                    // Filter hanya jurusan Teknologi Informasi
                    if ($item['jurusan'] === 'Teknologi Informasi') {
                        // Sinkronisasi Jurusan
                        $jurusan = Jurusan::firstOrCreate([
                            'id' => $item['kodeJurusan']
                        ], [
                            'nama_jurusan' => $item['jurusan']
                        ]);

                        // Sinkronisasi Prodi
                        $prodi = Prodi::firstOrCreate([
                            'id' => $item['kodeProdi']
                        ], [
                            'nama_prodi' => $item['prodi'],
                            'jurusan_id' => $jurusan->id
                        ]);

                        // Sinkronisasi Alumni
                        Alumni::updateOrCreate(
                            ['nim' => $item['nim']],
                            [
                                'nama_alumni' => $item['nama'],
                                'email' => $item['email'],
                                'no_telepon' => $item['telepon'],
                                'angkatan' => $item['tahunAkademik'],
                                'prodi_id' => $prodi->id,
                                'jenjang' => $item['jenjang'],
                                // 'npwp' => 0,
                                'nik' => 0,
                                'password' => Hash::make('password'),
                                'jenis_kelamin' => 'N/A',
                                'tahun_lulus' => 0,
                                'gelombang_wisuda' => 1,
                                // 'alamat' => 'N/A',
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
