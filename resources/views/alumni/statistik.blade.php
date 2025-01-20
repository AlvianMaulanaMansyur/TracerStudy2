@extends('layouts.app')

@section('content')

<div class="relative">
    <img id="beranda" src="{{ asset('images/foto_login.jpg') }}" alt="Tracer Study" class="w-full h-screen object-cover">
    <div class="absolute inset-0 flex flex-col justify-center items-start text-white bg-black/50 p-4 z-10 animate__animated animate__fadeIn">
        <p class="text-2xl ml-28">Selamat Datang!</p>
        <h1 class="text-3xl font-bold ml-28">Di Halaman Statistik Lulusan<br>Jurusan Teknologi Informasi</h1>
        <p class="ml-28">Statistik Lulusan Jurusan Teknologi Informasi dapat dilihat <br> dibawah ini</p>
        <a href="{{ route('kuesioner.alumni.index') }}"
            class="bg-yellow-500 hover:underline hover:bg-yellow-600 text-black py-2 px-4 rounded shadow mt-4 transition duration-200 ml-28 animate__animated animate__fadeInDown">
            Isi Kuesioner
        </a>
    </div>
</div>

<div class="container mx-auto">

    <h2 id="Statistik" class="mt-36 text-3xl font-bold">Statistik Tracer Study</h2>
    <p class="mt-3">Berikut ini adalah statistik dari jurusan teknologi informasi</p>
    <div class="flex justify-center mt-4">
        <div class="flex flex-col items-center list-none mx-12">
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1">
            <li id="jumlahMahasiswa" class="text-2xl font-semibold">{{ $totalMahasiswaAktif}}li>
            <li class="text-sm text-2xl">Jumlah Mahasiswa Aktif</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12">
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1">
            <li id="jumlahAlumni" class="text-2xl font-semibold">{{ $totalAlumni}}</li>
            <li class="text-sm text-2xl">Jumlah Alumni</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12">
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1">
            <li id="sudahBekerja" class="text-2xl font-semibold"></li>
            <li class="text-sm text-2xl">Sudah Bekerja</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12">
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1">
            <li id="belumBekerja" class="text-2xl font-semibold"></li>
            <li class="text-sm text-2xl">Belum Bekerja</li>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const counters = [{
                    element: document.getElementById('jumlahMahasiswa'),
                    endValue: @json($totalMahasiswaAktif)
                },
                {
                    element: document.getElementById('jumlahAlumni'),
                    endValue: @json($totalAlumni)
                },
                {
                    element: document.getElementById('sudahBekerja'),
                    endValue: @json($datastatusalumni['jumlah_sudah'])
                },
                {
                    element: document.getElementById('belumBekerja'),
                    endValue: @json($datastatusalumni['jumlah_belum'])
                    
                }
            ];

            console.log(@json($datastatusalumni['jumlah_belum']))
            let hasAnimated = false;

            const options = {
                root: null,
                rootMargin: "0px",
                threshold: 0.1
            };

            const callback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !hasAnimated) {
                        animateCounters();
                        hasAnimated = true;
                        observer.unobserve(entry.target);
                    }
                });
            };

            const observer = new IntersectionObserver(callback, options);
            observer.observe(document.getElementById('Statistik'));

            function animateCounters() {
                counters.forEach(counter => {
                    let count = 0;
                    const increment = Math.ceil(counter.endValue / 100);

                    const updateCounter = () => {
                        count += increment;
                        if (count > counter.endValue) {
                            count = counter.endValue;
                        }
                        counter.element.textContent = count + "+";
                        if (count < counter.endValue) {
                            requestAnimationFrame(updateCounter);
                        }
                    };

                    updateCounter();
                });
            }
        });
    </script>

<div class="container mx-auto p-6 bg-white rounded-lg shadow-lg mt-36 mb-4 border border-gray-200">
    <h2 class="text-3xl font-bold text-gray-800">Chart Statistik</h2>
    <p class="mt-3 text-gray-600">Berikut ini adalah Chart Statistik dari jurusan teknologi informasi</p>

    <!-- Div untuk tombol -->
    <div class="mt-4 space-x-4" id="chart-buttons">
        <!-- Tombol akan diisi secara dinamis di sini -->
    </div>

    <!-- Canvas untuk Chart -->
    <div class="mt-10 mb-20">
        <div id="chart-container" class="bg-white-50 p-4 rounded-lg shadow mb-10 border border-gray-200"></div>
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

    // Data dari controller
    const chartsData = @json($charts); // Pastikan Anda mengirimkan data charts dari controller
    console.log(chartsData); // Debugging: Periksa data yang diterima

    // Mengisi tombol secara dinamis
    const chartButtonsContainer = document.getElementById('chart-buttons');
    Object.keys(chartsData).forEach((kuesionerId) => {
        const chartGroup = chartsData[kuesionerId];
        chartGroup.forEach((chart) => {
            const button = document.createElement('a');
            button.id = `show-chart-${chart.id}`;
            button.className = "bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg";
            button.innerText = chart.title; // Menggunakan judul chart sebagai teks tombol

            // Event Listener untuk tombol
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const options = {
                    chart: {
                        type: chart.chartType, // Jenis chart dinamis
                        height: 350
                    },
                    series: [{
                        name: chart.title,
                        data: chart.data.data // Data untuk chart
                    }],
                    xaxis: {
                        categories: chart.data.labels // Label untuk sumbu X
                    },
                    title: {
                        text: chart.title,
                        align: 'center',
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold'
                        }
                    },
                    ...getChartOptions(chart.chartType) // Mengambil opsi tambahan berdasarkan tipe chart
                };
                renderChart(options); // Render chart dengan opsi yang sesuai
            });

            chartButtonsContainer.appendChild(button);
        });
    });

    // Render grafik pertama secara default jika ada
    if (Object.keys(chartsData).length > 0) {
        const firstChart = chartsData[Object.keys(chartsData)[0]][0]; // Ambil chart pertama dari grup pertama
        const options = {
            chart: {
                type: firstChart.chartType,
                height: 350
            },
            series: [{
                name: firstChart.title,
                data: firstChart.data.data
            }],
            xaxis: {
                categories: firstChart.data.labels
            },
            title: {
                text: firstChart.title,
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold'
                }
            },
            ...getChartOptions(firstChart.chartType)
        };
        renderChart(options); // Render chart pertama sebagai default
    }

    // Fungsi untuk mendapatkan opsi tambahan berdasarkan tipe chart
    function getChartOptions(chartType) {
        switch(chartType) {
            case 'bar':
                return {
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    }
                };
            case 'line':
                return {
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 4
                    }
                };
            case 'pie':
                return {
                    legend: {
                        position: 'bottom'
                    }
                };
            case 'radar':
                return {
                    stroke: {
                        width: 2
                    },
                    markers: {
                        size: 4
                    }
                };
            case 'scatter':
                return {
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        size: 6,
                        colors: ['#FF5733'],
                        shape: 'circle',
                        strokeWidth: 2,
                        hover: {
                            size: 8
                        }
                    }
                };
            default:
                return {}; // Opsi default jika tipe tidak dikenali
        }
    }
</script>
</div>

@endsection