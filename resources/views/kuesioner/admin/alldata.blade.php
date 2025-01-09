@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Halaman</h1>
    
    <a href="{{ route('kuesioner.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg mb-3 inline-block">Buat Halaman Baru</a>
    
    @if ($halamans->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
            Tidak ada halaman yang tersedia.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border-b text-center">ID Halaman</th>
                        <th class="py-2 px-4 border-b text-center">Judul Halaman</th>
                        <th class="py-2 px-4 border-b text-center">Deskripsi Halaman</th>
                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($halamans as $halaman)
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b text-center">{{ $halaman->id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $halaman->judul_halaman }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $halaman->deskripsi_halaman }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <a href="{{ route('kuesioner.show', $halaman->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded-lg">Lihat</a>
                                <a href="{{ route('kuesioner.edit', $halaman->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded-lg">Edit</a>
                                <form action="{{ route('halaman.destroy', $halaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menghapus halaman ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4 mt-8">Daftar Pertanyaan</h1>
    
    @if ($pertanyaans->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
            Tidak ada pertanyaan yang tersedia.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border-b text-center">ID Pertanyaan</th>
                        <th class="py-2 px-4 border-b text-center">Teks Pertanyaan</th>
                        <th class="py-2 px-4 border-b text-center">Halaman</th>
                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pertanyaans as $pertanyaan)
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b text-center">{{ $pertanyaan->id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ json_decode($pertanyaan->data_pertanyaan)->teks_pertanyaan }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $pertanyaan->halaman->judul_halaman }}</td <td class="py-2 px-4 border-b text-center">
                                <a href="{{ route('pertanyaan.show', $pertanyaan->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded-lg">Lihat</a>
                                <a href="{{ route('pertanyaan.edit', $pertanyaan->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded-lg">Edit</a>
                                <form action="{{ route('pertanyaan.destroy', $pertanyaan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4 mt-8">Daftar Logika</h1>
    
    @if ($logikas->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
            Tidak ada logika yang tersedia.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border-b text-center">ID Logika</th>
                        <th class="py-2 px-4 border-b text-center">Teks Logika</th>
                        <th class="py-2 px-4 border-b text-center">Pertanyaan ID</th>
                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logikas as $logika)
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b text-center">{{ $logika->id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ json_decode($logika->data_pertanyaan)->teks_pertanyaan }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $logika->pertanyaan_id }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <a href="{{ route('logika.show', $logika->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded-lg">Lihat</a>
                                <a href="{{ route('logika.edit', $logika->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded-lg">Edit</a>
                                <form action="{{ route('logika.destroy', $logika->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menghapus logika ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection