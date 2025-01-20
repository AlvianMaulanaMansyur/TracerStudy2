@extends('layouts.admin')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Kuesioner</h1>
    
    <a href="{{ route('kuesioner.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg mb-3 inline-block">Buat Kuesioner Baru</a>
    
    @if ($kuesioners->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
            Tidak ada kuesioner yang tersedia.
        </div>
    @else
    <div>
        
    </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border-b text-center">ID</th>
                        <th class="py-2 px-4 border-b text-center">Judul Kuesioner</th>
                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kuesioners as $kuesioner)
    <tr class="hover:bg-gray-100">
        <td class="py-2 px-4 border-b text-center">{{ $kuesioner->id }}</td>
        <td class="py-2 px-4 border-b text-center">{{ $kuesioner->judul_kuesioner }}</td>
        <td class="py-2 px-4 border-b text-center">
            {{-- {{ $kuesioner->slug }} --}}
            @if (isset($halamanPertamaIds[$kuesioner->id]))
            <a href="{{ route('kuesioner.page', ['slug' => $kuesioner->slug, 'halamanId' => $halamanPertamaIds[$kuesioner->id]]) }}" class="bg-green-500 text-white px-2 py-1 rounded-lg">Lihat</a>
        @endif
            {{-- <a href="{{ route('kuesioner.show', $kuesioner->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded-lg">Lihat</a> --}}
            <a href="{{ route('kuesioner.edit', $kuesioner->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded-lg">Edit</a>
            <form action="{{ route('kuesioner.destroy', $kuesioner->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menghapus kuesioner ini?')">Hapus</button>
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