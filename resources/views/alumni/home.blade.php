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
    <p class="text-center mt-3">Berikut ini adalah statistik dari jurusan teknologi informasi</p>
    <div class="flex justify-center mt-4"> <!-- Memusatkan elemen -->
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
            <li id="sudahBekerja" class="text-2xl font-semibold">0</li>
            <li class="text-sm text-2xl">Sudah Bekerja</li>
        </div>
        <div class="flex flex-col items-center list-none mx-12">
            <img src="{{ asset('images/kelompok_orang.png') }}" alt="Tracer Study" class="w-48 h-auto mb-1">
            <li id="belumBekerja" class="text-2xl font-semibold">0</li>
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


    <div class="container mx-auto p-6 bg-white rounded-lg shadow-lg mt-36 mb-4 border border-gray-200 max-w-6xl"> <!-- Box wrapper -->
        <h2 class="text-center text-3xl font-bold">Chart Statistik</h2>
        <p class="text-center mt-3">Berikut ini adalah Chart Statistik dari jurusan teknologi informasi</p>
        <!-- Div untuk tombol -->
        <div class="flex justify-center mt-4 space-x-4">
            <a id="show-pie-chart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg">Chart 1</a>
            <a id="show-line-chart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg">Chart 2</a>
            <a id="show-bar-chart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg">Chart 3</a>
            <a id="show-lineMahasiswa-chart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition duration-200 cursor-pointer shadow-md hover:shadow-lg">Chart 4</a>
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
        // linechart alumni
        const resultArray = @json($angkatanPerTahunLulus);

        // Menyiapkan kategori dan data
        const categories = resultArray.map(item => item.tahun_lulus); // Mengambil tahun lulus sebagai kategori
        const data = resultArray.map(item => item.total); // Mengambil total alumni sebagai data

        console.log(resultArray); // Menampilkan hasil di console
        console.log(categories); // Menampilkan kategori di console
        console.log(data); // Menampilkan data di console

        // Opsi Grafik
        const lineOptions = {
                series: [{
                    name: 'Jumlah Alumni',
                    data: data // Mengambil total alumni
                }],
                chart: {
                    height: 350,
                    type: 'line'
                },
                title: {
                    text: 'Jumlah Alumni per Tahun Lulus', // Judul chart
                    align: 'center', // Posisi judul
                    style: {
                        fontSize: '15px', // Ukuran font
                        fontWeight: 'bold' // Ketebalan font
                    }
                },
                    xaxis: {
                        categories: categories // Mengambil tahun lulus sebagai kategori
                    }
                };

                // linechart pertumbuhan Mahasiswa
                const resultarray = @json($angkatanPerTahun);

                // Menyiapkan kategori dan data
                const categorie = resultarray.map(item => item.angkatan); // Mengambil tahun lulus sebagai kategori
                const datas = resultarray.map(item => item.total); // Mengambil total alumni sebagai data

                console.log(resultarray); // Menampilkan hasil di console
                console.log(categorie); // Menampilkan kategori di console
                console.log(datas); // Menampilkan data di console

                // Opsi Grafik
                const lineMahasiswaOptions = {
                    series: [{
                        name: 'Jumlah Mahasiswa',
                        data: datas // Mengambil total mahasiswa
                    }],
                    chart: {
                        height: 350,
                        type: 'line'
                    },
                    title: {
                    text: 'Jumlah Mahasiswa per Tahun Angkatan', // Judul chart
                    align: 'center', // Posisi judul
                    style: {
                        fontSize: '15px', // Ukuran font
                        fontWeight: 'bold' // Ketebalan font
                    }
                },
                    xaxis: {
                        categories: categorie // Mengambil angkatan sebagai kategori
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

                // Mengonversi data jumlahAlumni ke format JavaScript
                const jumlahAlumni = @json($jumlahAlumni);
                console.log(jumlahAlumni)

                // Mengambil total dari setiap prodi
                const seriesData = jumlahAlumni.map(item => item.total);

                // Menggunakan seriesData dalam pieOptions
                const pieOptions = {
                    chart: {
                        type: 'pie',
                        height: 350
                    },
                    series: seriesData, // Menggunakan data yang telah diproses
                    labels: ['MI', 'TRPL', 'AJK'], // Pastikan label sesuai dengan prodi_id
                    title: {
                        text: 'Alumni Jurusan Teknologi Informasi',
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
                document.getElementById('show-lineMahasiswa-chart').addEventListener('click', function(event) {
                    event.preventDefault();
                    renderChart(lineMahasiswaOptions);
                });

                // Render grafik pie secara default
                renderChart(pieOptions);
    </script>
</div>

@endsection