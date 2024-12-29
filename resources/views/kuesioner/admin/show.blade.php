@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Kuesioner</h1>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h2>{{ $kuesioner->judul_kuesioner }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Deskripsi:</strong></p>
            <p>{{ $kuesioner->deskripsi }}</p>

            <h3>Pertanyaan:</h3>
            @csrf
           <div>{{ $filteredQuestions }}</div>
           @foreach ($filteredQuestions as $pertanyaan)  <!-- Menggunakan $filteredQuestions yang sudah difilter -->
           @php
               $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan);
           @endphp
           <div class="mb-3">
               <label class="form-label">{{ $dataPertanyaan->pertanyaan }}</label>
               @if ($dataPertanyaan->tipe_pertanyaan === 'text')
                   <input type="text" name="jawaban[{{ $pertanyaan->id }}]" class="form-control" placeholder="Jawaban...">
               @elseif ($dataPertanyaan->tipe_pertanyaan === 'checkbox')
                   @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                       <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="jawaban[{{ $pertanyaan->id }}][]" value="{{ $opsi }}" id="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}">
                           <label class="form-check-label" for="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}">
                               {{ $opsi }}
                           </label>
                       </div>
                   @endforeach
               @elseif ($dataPertanyaan->tipe_pertanyaan === 'radio')
                   @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                       <div class="form-check">
                           <input class="form-check-input" type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $opsi }}" id="radio_{{ $pertanyaan->id }}_{{ $loop->index }}">
                           <label class="form-check-label" for="radio_{{ $pertanyaan->id }}_{{ $loop->index }}">
                               {{ $opsi }}
                           </label>
                       </div>
                   @endforeach
               @else
                   <p>Tipe pertanyaan tidak dikenali.</p>
               @endif
           </div>
       @endforeach
        </div>
        <div class="card-footer">
            <a href="{{ route('kuesioner.index') }}" class="btn btn-secondary">Kembali ke Daftar Kuesioner</a>
            <a href="{{ route('kuesioner.edit', $kuesioner->id) }}" class="btn btn-warning">Edit Kuesioner</a>
            <form action="{{ route('kuesioner.destroy', $kuesioner->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kuesioner ini?')">Hapus Kuesioner</button>
            </form>
            <div>{{ $currentPage }}</div> 
            <div>{{ $totalPages }}</div>

            @if ($currentPage < $totalPages) <!-- Menampilkan tombol Next jika masih ada halaman berikutnya -->
            <a href="{{ route('kuesioner.admin.show', ['id' => $kuesioner->id, 'page' => $currentPage + 1]) }}" class="btn btn-primary">Next</a>
        @endif
        </div>
    </div>
</div>
@endsection