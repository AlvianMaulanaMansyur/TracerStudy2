@extends('layouts.admin')

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
                        <th class="py-2 px-4 border-b text-center">ID Kuesioner</th>
                        <th class="py-2 px-4 border-b text-center">Nama Kuesioner</th>
                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kuesioners as $kuesioner)
                        <tr>
                            <td class="py-2 px-4 border-b text-center">{{ $kuesioner->id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $kuesioner->judul_kuesioner }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <a href="{{ route('admin.chart.create', ['kuesionerId' => $kuesioner->id]) }}" class="text-blue-500 hover:underline">Buat Chart</a>
                                <span class="mx-2">|</span>
                                <a href="{{ route('admin.chart.show', ['kuesionerId' => $kuesioner->id]) }}" class="text-blue-500 hover:underline">Lihat Chart</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection