@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Rekap Data Responden</h1>

        <div class="mb-6">
            <label for="kuesioner_id" class="block text-sm font-medium text-gray-700">Pilih Judul Kuesioner</label>
            <select id="kuesioner_id" name="kuesioner_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="" selected>-- Pilih Judul Kuesioner --</option>
                @foreach ($kuesioners as $kuesioner)
                    <option value="{{ $kuesioner->id }}"
                        {{ isset($kuesionerId) && $kuesionerId == $kuesioner->id ? 'selected' : '' }}>
                        {{ $kuesioner->judul_kuesioner }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label for="prodi" class="block text-sm font-medium text-gray-700">Filter Prodi</label>
            <select id="prodi" name="prodi" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" >
                <option value="" selected>-- Semua Prodi --</option>
                <option value="Teknologi Rekayasa Perangkat Lunak"
                    {{ isset($prodiFilter) && $prodiFilter == 'Teknologi Rekayasa Perangkat Lunak' ? 'selected' : '' }}>
                    Teknologi Rekayasa Perangkat Lunak
                </option>
                <option value="Manajemen Informatika"
                    {{ isset($prodiFilter) && $prodiFilter == 'Manajemen Informatika' ? 'selected' : '' }}>
                    Manajemen Informatika
                </option>
                <option value="Administrasi Jaringan Komputer"
                    {{ isset($prodiFilter) && $prodiFilter == 'Administrasi Jaringan Komputer' ? 'selected' : '' }}>
                    Administrasi Jaringan Komputer
                </option>
            </select>
        </div>

        <!-- Tabel Responden -->
        <div class="card rounded-sm shadow-md">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Alumni</th>
                            <th>Prodi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-content">
                        @if (isset($paginated))
                            @foreach ($paginated as $index => $responden)
                                <tr>
                                    <td>{{ $paginated->firstItem() + $index }}</td>
                                    <td>{{ $responden['nim'] }}</td>
                                    <td>{{ $responden['nama'] }}</td>
                                    <td>{{ $responden['prodi'] }}</td>
                                    {{-- <td>{{ $responden['id'] }}</td> --}}

                                    <td>
                                        @if ($responden['status'] === 'sudah')
                                            <span class="text-success">✅ Sudah</span>
                                        @else
                                            <span class="text-danger">❌ Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rekap.jawaban', ['slug' => $kuesioner->slug, 'nim' => $responden['nim']]) }}" class="btn btn-primary btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">Silakan pilih kuesioner untuk melihat data.</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
                <div id="pagination-wrapper">
                    <div id="pagination-wrapper">
                        @if (isset($paginated))
                            {{ $paginated->links('pagination.custom') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script>
            const kuesionerDropdown = document.getElementById('kuesioner_id');
            const prodiDropdown = document.getElementById('prodi');

            // Aktifkan dropdown prodi jika kuesioner dipilih
            kuesionerDropdown.addEventListener('change', function() {
                const kuesionerId = this.value;
                
                prodiDropdown.disabled = !kuesionerId; // Aktifkan atau nonaktifkan dropdown prodi

                if (kuesionerId) {
                    const prodi = prodiDropdown.value;
                    let url = `/admin/rekap/show?kuesioner_id=${kuesionerId}`;
                    if (prodi) {
                        url += `&prodi=${prodi}`;
                    }
                    window.location.href = url; // Redirect saat kuesioner dipilih
                }
            });

            // Redirect jika prodi dipilih (hanya jika kuesioner sudah dipilih)
            prodiDropdown.addEventListener('change', function() {
                const kuesionerId = kuesionerDropdown.value;
                const prodi = this.value;
                if (kuesionerId) {
                    let url = `/admin/rekap/show?kuesioner_id=${kuesionerId}`;
                    if (prodi) {
                        url += `&prodi=${prodi}`;
                    }
                    window.location.href = url; // Redirect dengan filter prodi
                }
            });
        </script>
    @endsection
