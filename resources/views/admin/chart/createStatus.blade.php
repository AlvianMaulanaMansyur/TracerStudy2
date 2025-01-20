@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">Buat Data Status Bekerja Alumni</h1>

<form id="questionForm" method="POST" action="{{ route('admin.status.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="type" id="typeInput" value="">

    <div class="flex flex-col">
        <label for="questionOrLogikaId" class="mb-1 font-medium">Pilih Pertanyaan Untuk Dijadikan Status Bekerja</label>
        <select id="questionOrLogikaId" name="questionOrLogikaId" 
            class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required>
            <option value="" selected disabled>Pilih Pertanyaan</option>
            @foreach($pertanyaans as $pertanyaan)
                <option value="{{ $pertanyaan['id'] }}" data-type="pertanyaan" 
                    data-opsi="{{ json_encode($pertanyaan['opsi_jawaban']) }}">
                    {{ $pertanyaan['id'] }} - {{ $pertanyaan['teks_pertanyaan'] }}
                </option>
            @endforeach
            @foreach($semuaLogika as $logika)
                <option value="{{ $logika['id'] }}" data-type="logika" 
                    data-opsi-logika="{{ json_encode($logika['opsi_jawaban_logika']) }}">
                    {{ $logika['id'] }} - {{ $logika['teks_logika'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col">
        <label for="opsiJawabanContainer" class="mb-1 font-medium text-gray-700">
            Pilih Opsi 
        </label>
        <div id="opsiJawabanContainer" 
            class="border border-gray-300 rounded-lg p-4 space-y-2 bg-gray-50">
            <!-- Pilihan akan diisi secara dinamis -->
        </div>
    </div>

    <!-- Input untuk label jika opsi tidak sesuai -->
    <div id="labelContainer" class="hidden">
        <label for="customLabel" class="mb-1 font-medium text-gray-700">Masukkan Label Opsi:</label>
        <input type="text" id="customLabel" name="customLabel" 
            class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            placeholder="Label Opsi">
    </div>

    <div class="flex justify-start">
        <button type="submit" 
            class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Simpan Data
        </button>
    </div>
</form>

<!-- Elemen untuk menampilkan chart -->
<div id="chart" class="mt-8"></div>

<script>
     document.addEventListener('DOMContentLoaded', function() {
        // Data chart dari controller
        const chartData = @json($charts);
        console.log(chartData);

        // Siapkan data untuk ApexCharts
        const options = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Status Bekerja',
                data: chartData[0].data.data // Ambil data dari chart
            }],
            xaxis: {
                categories: chartData[0].data.labels // Ambil labels dari chart
            },
            title: {
                text: chartData[0]. title // Ambil judul dari chart
            }
        };

        // Membuat chart
        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
    document.getElementById('questionOrLogikaId').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const type = selectedOption.getAttribute('data-type');

        // Set nilai type ke input hidden
        document.getElementById('typeInput').value = type;

        // Clear previous checkboxes
        const opsiJawabanContainer = document.getElementById('opsiJawabanContainer');
        opsiJawabanContainer.innerHTML = '';

        // Clear label input
        const labelContainer = document.getElementById('labelContainer');
        labelContainer.classList.add('hidden');
        document.getElementById('customLabel').value = '';

        if (type === 'pertanyaan') {
            const opsiJawaban = JSON.parse(selectedOption.getAttribute('data-opsi'));

            // Tambahkan checkbox untuk setiap opsi jawaban
            opsiJawaban.forEach((opsi, index) => {
                const checkbox = document.createElement('div');
                checkbox.innerHTML = `
                    <input type="checkbox" id="opsi${index}" name="opsiJawaban[]" value="${opsi.opsiJawaban}" onchange="checkOpsi()">
                    <label for="opsi${index}">${opsi.opsiJawaban}</label>
                `;
                opsiJawabanContainer.appendChild(checkbox);
            });
        } else if (type === 'logika') {
            const opsiJawabanLogika = JSON.parse(selectedOption.getAttribute('data-opsi-logika'));

            // Tambahkan checkbox untuk setiap opsi jawaban logika
            opsiJawabanLogika.forEach((opsi, index) => {
                const checkbox = document.createElement('div');
                checkbox.innerHTML = `
                    <input type="checkbox" id="logikaOpsi${index}" name="opsiJawabanLogika[]" value="${opsi}" onchange="checkOpsi()">
                    <label for="logikaOpsi${index}">${opsi}</label>
                `;
                opsiJawabanContainer.appendChild(checkbox);
            });
        }

    });

    function checkOpsi() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        const validOptions = ['sudah bekerja', 'belum bekerja']; // Daftar opsi yang valid
        const labelContainer = document.getElementById('labelContainer');
        const customLabelInput = document.getElementById('customLabel');
        let allInvalid = true;

        checkboxes.forEach(checkbox => {
            if (validOptions.includes(checkbox.value.toLowerCase())) {
                allInvalid = false; // Set allInvalid ke false jika ada opsi yang valid
            }
        });

        // Tampilkan atau sembunyikan input label berdasarkan status opsi
        if (allInvalid && checkboxes.length > 0) {
            labelContainer.classList.remove('hidden'); // Tampilkan input label
        } else {
            labelContainer.classList.add('hidden'); // Sembunyikan input label
            customLabelInput.value = ''; // Kosongkan input label
        }
    }
</script>
@endsection