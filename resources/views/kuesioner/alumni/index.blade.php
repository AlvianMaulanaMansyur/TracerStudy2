@extends('layouts.alumniDashboard')

@section('content')
    <div class="container mx-auto mt-5 px-4">
        <h1 class="text-2xl font-bold mb-4">Daftar Kuesioner</h1>

        @if ($kuesioners->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
                Tidak ada kuesioner yang tersedia.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-2 px-4 border-b text-center">Judul Kuesioner</th>
                            <th class="py-2 px-4 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kuesioners as $kuesioner)
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b text-center">{{ $kuesioner->judul_kuesioner }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    @if (isset($halamanPertamaIds[$kuesioner->id]))
                                        @if (isset($kuesionerSudahDiisi[$kuesioner->id]))
                                            <span class="text-gray-500">Sudah diisi</span>
                                            <a href="{{ route('kuesioner.alumni.jawaban', ['slug' => $kuesioner->slug]) }}" class="bg-green-500 text-white px-2 py-1 rounded-lg">Lihat Jawaban</a>
                                        @else
                                        
                                            <a href="{{ route('kuesioner.alumni.page', ['slug' => $kuesioner->slug, 'halamanId' => $halamanPertamaIds[$kuesioner->id]]) }}"
                                                class="bg-green-500 text-white px-2 py-1 rounded-lg">Isi Kuesioner</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection