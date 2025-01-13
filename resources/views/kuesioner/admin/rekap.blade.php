@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Rekap Data Responden</h1>

        <!-- Dropdown Pilih Kuesioner -->
        <div class="mb-3">
            <select id="kuesioner_id" name="kuesioner_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="" disabled {{ isset($kuesionerId) ? '' : 'selected' }}>-- Pilih Judul Kuesioner --
                </option>
                @foreach ($kuesioners as $kuesioner)
                    <option value="{{ $kuesioner->id }}"
                        {{ isset($kuesionerId) && $kuesioner->id == $kuesionerId ? 'selected' : '' }}>
                        {{ $kuesioner->judul_kuesioner }}
                    </option>
                @endforeach
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
                                    <td>
                                        @if ($responden['status'] === 'sudah')
                                            <span class="text-success">✅ Sudah</span>
                                        @else
                                            <span class="text-danger">❌ Belum</span>
                                        @endif
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
            document.getElementById('kuesioner_id').addEventListener('change', function() {
                const kuesionerId = this.value;
                window.location.href = `/admin/rekap/show?kuesioner_id=${kuesionerId}`;
            });
        </script>
    @endsection
