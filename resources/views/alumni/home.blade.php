@extends('layouts.app')

@section('content')

<div class="relative">
    <img id="beranda" src="{{ asset('images/foto_login.jpg') }}" alt="Tracer Study" class="w-full h-screen object-cover">
    <div class="absolute inset-0 flex flex-col justify-center items-start text-white bg-black/50 p-4 z-10">
        <p class="text-2xl ml-28">Selamat Datang!</p>
        <h1 class="text-3xl font-bold ml-28">Di Tracer Study<br>Jurusan Teknologi Informasi</h1>
        <p class="ml-28"> Ini adalah halaman home untuk alumni.</p>
        <a href="{{ route('kuesioner.alumni.index') }}"
            class="bg-yellow-500 hover:underline hover:bg-yellow-600 text-black py-2 px-4 rounded shadow mt-4 transition duration-200 ml-28">
            Isi Kuesioner
        </a>
    </div>
</div>

<div class="container mx-auto">
    <h2 id="tracer_study" class="mt-32 text-center text-3xl font-bold">Tracer Study</h2>
    <p class="mt-4 text-center ">
        Tracer study adalah survei yang dilakukan untuk melacak jejak lulusan suatu institusi pendidikan setelah mereka menyelesaikan studi.
        Tujuan utama dari tracer study adalah untuk mengumpulkan <br> data dan informasi mengenai keberadaan, aktivitas, dan situasi karier lulusan.
        Melalui survei ini, institusi dapat mengevaluasi efektivitas program pendidikan yang telah diberikan dan memahami <br> bagaimana lulusan berkontribusi di dunia kerja.
        Data yang diperoleh dari tracer study juga dapat digunakan untuk meningkatkan kurikulum dan pengembangan program yang lebih relevan dengan <br> kebutuhan industri.
        Data yang diperoleh dari tracer study juga dapat digunakan untuk meningkatkan kurikulum dan pengembangan program yang lebih relevan dengan kebutuhan industri <br>
    <p>

    <h2 id="Statistik" class="mt-36 text-center text-3xl font-bold">Statistik Tracer Study</h2>
    <p class="text-center mt-3">Berikut ini adalah statistik lulusan dari jurusan teknologi informasi</p>
    <div class="flex justify-center mt-4"> <!-- Memusatkan elemen -->
        <div class="flex flex-col items-center list-none mx-12"> <!-- Mengubah margin horizontal menjadi mx-4 -->
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1"> <!-- Mengurangi margin bawah -->
            <li class="text-lg font-semibold">0</li>
            <li class="text-sm">Jumlah Alumni</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12"> <!-- Mengubah margin horizontal menjadi mx-4 -->
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1"> <!-- Mengurangi margin bawah -->
            <li class="text-lg font-semibold">0</li>
            <li class="text-sm">Sudah Bekerja</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12"> <!-- Mengubah margin horizontal menjadi mx-4 -->
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1"> <!-- Mengurangi margin bawah -->
            <li class="text-lg font-semibold">0</li>
            <li class="text-sm">Belum Bekerja</li> <!-- Mengubah teks untuk variasi -->
        </div>
    </div>

    <h2 class="mt-36 text-center text-3xl font-bold">Chart Statistik</h2>
    <p class="text-center mt-3">Berikut ini adalah Chart Statistik lulusan dari jurusan teknologi informasi</p>
    <!-- Div untuk tombol -->
    <div class="flex justify-center mt-4 space-x-4">
        <a href="#" class="bg-yellow-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition duration-200">Chart 1</a>
        <a href="#" class="bg-yellow-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition duration-200">Chart 2</a>
        <a href="#" class="bg-yellow-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition duration-200">Chart 3</a>
    </div>
    <!-- Canvas untuk Chart -->
    <div class="flex justify-center mt-10">
        <div class=" w-96 h-96">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        let myChart;

        function showChart(chartType) {
            if (myChart) {
                myChart.destroy(); // Hancurkan chart yang ada sebelum membuat yang baru
            }

            const data = {
                labels: ['Label 1', 'Label 2', 'Label 3'],
                datasets: [{
                    label: 'My Dataset',
                    data: [10, 20, 30], // Data untuk chart
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: chartType, // Tipe chart yang dipilih
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Pie Chart'
                        }
                    }
                }
            };

            myChart = new Chart(ctx, config);
        }

        // Tampilkan chart pertama secara default
        showChart('pie'); // Ganti 'pie' dengan 'bar', 'line', dll. sesuai kebutuhan
    </script>

    <!-- <h2 class="text-2xl font-bold mt-36">Informasi Terbaru</h2>
    <ul class="list-group">
        <li class="list-group-item">Acara reuni akan diadakan pada tanggal 20 Desember 2023.</li>
        <li class="list-group-item">Silakan perbarui informasi kontak Anda di profil alumni.</li>
        <li class="list-group-item">Jangan lewatkan kesempatan untuk berkontribusi pada program beasiswa.</li>
    </ul>

    
    <div class="text-center mt-36">
        @if(auth()->check())
        <a href="{{ route('kuesioner.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition duration-200">Lihat Kuesioner</a>
        @else
        <a href="{{ route('alumni.login') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black py-2 px-4 rounded transition duration-200">Login untuk Mengakses Kuesioner</a>
        @endif
    </div> -->
</div>

@endsection