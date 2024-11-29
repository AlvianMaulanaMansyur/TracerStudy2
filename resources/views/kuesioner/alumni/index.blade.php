<!-- resources/views/kuesioner/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Kuesioner</h1>
    
    <a href="{{ route('kuesioner.create') }}" class="btn btn-primary mb-3">Buat Kuesioner Baru</a>
    
    @if ($kuesioner->isEmpty())
        <p>Tidak ada kuesioner yang tersedia.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Kuesioner</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kuesioner as $kuesioners)
                    <tr>
                        <td>{{ $kuesioners->id }}</td>
                        <td>{{ $kuesioners->judul_kuesioner }}</td>
                        <td>
                            <a href="{{ route('kuesioner.alumni.show', $kuesioners->id) }}" class="btn btn-info btn-sm">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
