@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Statistik Kuesioner</h1>

    @foreach ($statistik as $pertanyaanId => $data)
        <h2 class="text-lg font-semibold">{{ $data['pertanyaan'] }}</h2>
        <canvas id="chart-{{ $pertanyaanId }}" class="mb-6"></canvas>
        <script>
            var ctx = document.getElementById('chart-{{ $pertanyaanId }}').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Jenis grafik (bar, pie, line, dll)
                data: {
                    labels: {!! json_encode(array_keys($data['jawaban'])) !!}, // Label untuk sumbu X
                    datasets: [{
                        label: 'Jumlah Jawaban',
                        data: {!! json_encode(array_values($data['jawaban'])) !!}, // Data untuk sumbu Y
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endforeach
</div>
@endsection