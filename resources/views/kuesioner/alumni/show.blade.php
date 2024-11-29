<!-- resources/views/kuesioner/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Kuesioner</h1>
    
    <div class="card">
        <div class="card-header">
            <h2>{{ $kuesioner->judul_kuesioner }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Deskripsi:</strong></p>
            <p>{{ $kuesioner->deskripsi }}</p> <!-- Pastikan ada kolom deskripsi di model Kuesioner -->

            <h3>Pertanyaan:</h3>
                @csrf
                @foreach ($kuesioner->pertanyaan as $pertanyaan) 
                    @php
                        $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan);
                    @endphp
                    <div class="mb-3">
                        <label class="form-label">{{ $dataPertanyaan->pertanyaan }}</label>
                        @if ($dataPertanyaan->tipe_pertanyaan === 'text')
                            <input type="text" name="jawaban[{{ $pertanyaan->id }}]" class="form-control" placeholder="Jawaban...">
                        @elseif ($dataPertanyaan->tipe_pertanyaan === 'multiple_choice')
                            @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="jawaban[{{ $pertanyaan->id }}][]" value="{{ $opsi }}" id="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}">
                                    <label class="form-check-label" for="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}">
                                        {{ $opsi }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p>Tipe pertanyaan tidak dikenali.</p>
                        @endif
                    </div>
                @endforeach
                {{-- <button type="submit" class="btn btn-primary">Kirim Jawaban</button> --}}
            {{-- </form> --}}
        </div>
    </div>
</div>
@endsection