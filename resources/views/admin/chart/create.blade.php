@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">Buat Chart</h1>

<form id="questionForm" method="POST" action="{{ route('admin.chart.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="kuesioner_id" value="{{ $kuesionerId }}">
    <input type="hidden" name="type" id="typeInput" value=""> <!-- Input hidden untuk menyimpan type -->

    <!-- Judul Chart -->
    <div class="flex flex-col">
        <label for="judulChart" class="mb-1 font-medium">Judul Chart</label>
        <input type="text" name="judulChart" id="judulChart" placeholder="Masukkan judul chart" 
            class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required>
    </div>

    <div class="flex flex-col">
        <label for="questionOrLogikaId" class="mb-1 font-medium">Pilih Pertanyaan</label>
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

    <!-- Pilihan Tipe Chart -->
    <div class="flex flex-col">
        <label for="chartType" class="mb-1 font-medium">Pilih Tipe Chart</label>
        <select name="chartType" id="chartType" 
            class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required>
            <option value="bar">Bar</option>
            <option value="line">Line</option>
            <option value="pie">Pie</option>
            <option value="radar">Radar</option>
            <option value="scatter">Scatter</option>
        </select>
    </div>

    <!-- Pilihan Opsi Jawaban -->
    <div class="flex flex-col">
        <label for="opsiJawabanContainer" class="mb-1 font-medium text-gray-700">
            Pilih Opsi Jawaban untuk Dijadikan Chart 
            <span class="text-sm text-gray-500 italic">(Kosongkan pilihan jika ingin memilih semua opsi)</span>
        </label>
        <div id="opsiJawabanContainer" 
            class="border border-gray-300 rounded-lg p-4 space-y-2 bg-gray-50">
            <!-- Pilihan akan diisi secara dinamis -->
        </div>
    </div>

    <!-- Tombol Submit -->
    <div class="flex justify-end">
        <button type="submit" 
            class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Simpan Data Chart
        </button>
    </div>
</form>


<script>
    document.getElementById('questionOrLogikaId').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const type = selectedOption.getAttribute('data-type');

        // Set nilai type ke input hidden
        document.getElementById('typeInput').value = type;

        // Clear previous checkboxes
        const opsiJawabanContainer = document.getElementById('opsiJawabanContainer');
        opsiJawabanContainer.innerHTML = '';

        if (type === 'pertanyaan') {
            const opsiJawaban = JSON.parse(selectedOption.getAttribute('data-opsi'));

            // Tambahkan checkbox untuk setiap opsi jawaban
            opsiJawaban.forEach((opsi, index) => {
                const checkbox = document.createElement('div');
                checkbox.innerHTML = `
                    <input type="checkbox" id="opsi${index}" name="opsiJawaban[]" value="${opsi.opsiJawaban}">
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
                    <input type="checkbox" id="logikaOpsi${index}" name="opsiJawabanLogika[]" value="${opsi}">
                    <label for="logikaOpsi${index}">${opsi}</label>
                `;
                opsiJawabanContainer.appendChild(checkbox);
            });
        }
    });
</script>
@endsection