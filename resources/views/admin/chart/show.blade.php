@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Chart untuk Kuesioner : {{ $kuesioner->judul_kuesioner }}</h1>

<a href="{{ route('admin.chart.create', ['kuesionerId' => $kuesioner->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg mb-3 inline-block">Buat Chart</a>
<span class="mx-2">

@if (empty($charts))
<div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
    Belum ada chart yang di buat.
</div>
@endif

@foreach($charts as $chart)
<div class="flex items-center mb-4">
    <!-- Judul Chart -->
    <h2 class="text-xl font-semibold me-4">{{ $chart['title'] }}</h2>

    <!-- Tombol Hapus Chart -->
    <td class="py-2 px-4 border-b text-center">
        <button onclick="deleteChart({{ $chart['id'] }})" class="text-red-500 hover:underline">Hapus</button>
    </td>
</div>

    <div id="chart-{{ $loop->index }}" class="mb-6" aria-label="Chart untuk {{ $chart['title'] }}" role="img"></div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chart['data']); // Mengambil data chart dari controller

            const options = {
                chart: {
                    type: '{{ $chart['chartType'] }}', // Jenis chart dinamis
                    height: 350
                },
                series: [{
                    name: '{{ $chart['title'] }}',
                    data: chartData.data // Data untuk chart
                }],
                xaxis: {
                    categories: chartData.labels // Label untuk sumbu X
                },
                title: {
                    text: '{{ $chart['title'] }}',
                    align: 'center',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                ...getChartOptions('{{ $chart['chartType'] }}')
            };

            const chart = new ApexCharts(document.querySelector("#chart-{{ $loop->index }}"), options);
            chart.render();
        });

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

        function deleteChart(chartId) {
        // Tampilkan konfirmasi sebelum menghapus
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Lakukan permintaan AJAX untuk menghapus chart
                fetch(`/admin/chart/${chartId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tampilkan SweetAlert sukses
                        Swal.fire(
                            'Dihapus!',
                            data.success,
                            'success'
                        ).then(() => {
                            // Reload halaman atau hapus elemen chart dari DOM
                            location.reload(); // Reload halaman
                        });
                    } else {
                        // Tampilkan pesan error jika ada
                        Swal.fire(
                            'Error!',
                            data.error,
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
    </script>
@endforeach
@endsection
