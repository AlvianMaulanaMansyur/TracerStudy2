@extends('layouts.alumniDashboard')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Jawaban Kuesioner</h1>

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
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection