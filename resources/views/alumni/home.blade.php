@extends('layouts.app')

@section('content')

<div class="relative">
    <img id="beranda" src="{{ asset('images/foto_login.jpg') }}" alt="Tracer Study" class="w-full h-screen object-cover">
    <div class="absolute inset-0 flex flex-col justify-center items-start text-white bg-black/50 p-4 z-10 animate__animated animate__fadeIn">
        <p class="text-2xl ml-28">Selamat Datang!</p>
        <h1 class="text-3xl font-bold ml-28">Di Tracer Study<br>Jurusan Teknologi Informasi</h1>
        <p class="ml-28"> Ini adalah halaman home untuk alumni.</p>
        <a href="{{ route('kuesioner.alumni.index') }}"
            class="bg-yellow-500 hover:underline hover:bg-yellow-600 text-black py-2 px-4 rounded shadow mt-4 transition duration-200 ml-28 animate__animated animate__fadeInDown">
            Isi Kuesioner
        </a>
    </div>
</div>

<div class="container mx-auto">
    <h2 id="tracer_study" class="mt-32 text-center text-3xl font-bold ">Tracer Study</h2>
    <p class="mt-4 text-center ">
        Tracer study adalah survei yang dilakukan untuk melacak jejak lulusan suatu institusi pendidikan setelah mereka menyelesaikan studi. <br>
        Tujuan utama dari tracer study adalah untuk mengumpulkan data dan informasi mengenai keberadaan, aktivitas, dan situasi karier lulusan. <br>
        Melalui survei ini, institusi dapat mengevaluasi efektivitas program pendidikan yang telah diberikan dan memahami bagaimana lulusan <br> berkontribusi di dunia kerja.
        Data yang diperoleh dari tracer study juga dapat digunakan untuk meningkatkan kurikulum dan pengembangan program <br> yang lebih relevan dengan kebutuhan industri.
        Data yang diperoleh dari tracer study juga dapat digunakan untuk meningkatkan kurikulum dan pengembangan program <br>yang lebih relevan dengan kebutuhan industri <br>
    <p>

    
    <h2 id="Statistik" class=" mt-32 text-center text-3xl font-bold">Statistik Tracer Study</h2>
    <p class="text-center mt-3">Berikut ini adalah statistik lulusan dari jurusan teknologi informasi</p>
    <div class="flex justify-center mt-4"> <!-- Memusatkan elemen -->
        <div class="flex flex-col items-center list-none mx-12"> <!-- Mengubah margin horizontal menjadi mx-4 -->
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1"> <!-- Mengurangi margin bawah -->
            <li id="jumlahAlumni" class="text-lg font-semibold">0</li>
            <li class="text-sm">Jumlah Alumni</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12"> <!-- Mengubah margin horizontal menjadi mx-4 -->
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1"> <!-- Mengurangi margin bawah -->
            <li id="sudahBekerja" class="text-lg font-semibold">0</li>
            <li class="text-sm">Sudah Bekerja</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12"> <!-- Mengubah margin horizontal menjadi mx-4 -->
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1"> <!-- Mengurangi margin bawah -->
            <li id="belumBekerja" class="text-lg font-semibold">0</li>
            <li class="text-sm">Belum Bekerja</li> <!-- Mengubah teks untuk variasi -->
        </div>
    </div>


<div class="container mx-auto p-6 bg-white rounded-lg shadow-lg mt-36 mb-4 border border-gray-200 max-w-6xl"> <!-- Box wrapper -->
    <h2 class="text-center text-3xl font-bold">Chart Statistik</h2>
    <p class="text-center mt-3">Berikut ini adalah Chart Statistik lulusan dari jurusan teknologi informasi</p>
    <!-- Div untuk tombol -->
    <div class="flex justify-center mt-4 space-x-4">
        <a id="show-pie-chart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg">Chart 1</a>
        <a id="show-bar-chart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg">Chart 2</a>
        <a id="show-line-chart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg">Chart 3</a>
    </div>

    <!-- Canvas untuk Chart -->
    <div class="mt-10 mb-20">
        <div id="chart-container" class="bg-white-50 p-4 rounded-lg shadow mb-10 border border-gray-200 h-100"></div> <!-- Mengatur tinggi chart -->
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let chartInstance = null; // Variabel untuk menyimpan instans grafik

    // Fungsi untuk merender grafik
    const renderChart = (options) => {
        // Hancurkan instans grafik sebelumnya jika ada
        if (chartInstance) {
            chartInstance.destroy();
        }

        // Buat instans grafik baru
        chartInstance = new ApexCharts(document.querySelector("#chart-container"), options);
        chartInstance.render();
    };

    // Opsi Grafik
    const lineOptions = {
        series: [{
            name: 'Data',
            data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
        }],
        chart: {
            height: 350,
            type: 'line'
        },
        xaxis: {
            categories: ['2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029']
        }
    };

    const barOptions = {
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            name: 'Sales',
            data: [30, 40]
        }],
        xaxis: {
            categories: ['Sudah Bekerja', 'Belum Bekerja']
        },
        fill: {
            opacity: 1
        },
        title: {
            text: 'Grafik Mahasiswa Jurusan Teknologi Informasi',
            align: 'center'
        }
    };

    const pieOptions = {
        chart: {
            type: 'pie',
            height: 350
        },
        series: [44, 55, 13],
        labels: ['TRPL', 'MI', 'AJK'],
        title: {
            text: 'Mahasiswa Jurusan Teknologi Informasi',
            align: 'center'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    // Event Listener untuk tombol
    document.getElementById('show-pie-chart').addEventListener('click', function(event) {
        event.preventDefault();
        renderChart(pieOptions);
    });

    document.getElementById('show-bar-chart').addEventListener('click', function(event) {
        event.preventDefault();
        renderChart(barOptions);
    });

    document.getElementById('show-line-chart').addEventListener('click', function(event) {
        event.preventDefault();
        renderChart(lineOptions);
    });

    // Render grafik pie secara default
    renderChart(pieOptions);
</script>
</div>

@endsection