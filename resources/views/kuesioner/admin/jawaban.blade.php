@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="text-2xl font-bold mb-4">Jawaban Kuesioner</h1>

    @if ($jawabanKuesioner->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
        Belum mengisi kuesioner.
    </div>
    @else
        @foreach ($pertanyaan as $item)
        @php
            $dataPertanyaan = json_decode($item->data_pertanyaan);
            $jawaban = $jawabanKuesioner->where('pertanyaan_id', $item->id)->first();
            $jawabanLogikaItem = null; // Inisialisasi variabel
            if ($jawabanLogika) {
                $jawabanLogikaItem = $jawabanLogika->where('logika_id', $item->logika->pluck('id'))->first();
            }
        @endphp

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $dataPertanyaan->teks_pertanyaan }}</h5>
                <p class="card-text"><strong>Jawaban: </strong>{{ $jawaban ? $jawaban->jawaban : 'Belum dijawab' }}</p>

                @if ($item->logika->isNotEmpty())
                    <h6 class="mt-3">Pertanyaan Tambahan:</h6>
                    @foreach ($item->logika as $logika)
                        @php
                            $dataLogika = json_decode($logika->data_pertanyaan);
                            $jawabanLogikaItem = $jawabanLogika->where('logika_id', $logika->id)->first();
                        @endphp
                        <div class="mb-2">
                            <strong>{{ $dataLogika->teks_pertanyaan }}:</strong> 
                            <span class="text-muted">{{ $jawabanLogikaItem ? $jawabanLogikaItem->jawaban : '(Tidak dijawab)' }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Tidak ada pertanyaan tambahan untuk kuesioner ini.</p>
                @endif
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection