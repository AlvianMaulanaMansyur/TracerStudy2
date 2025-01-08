@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex gap-2 mb-4">
            <a href="{{ route('kuesioner.alumni.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Kembali ke
                Kuesioner</a>
        </div>
        <div class="bg-white shadow rounded-lg p-6 mb-6" id="halaman-{{ $halaman->id }}">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Halaman: {{ $halaman->judul_halaman }}</h2>

            @if ($pertanyaan->isNotEmpty())
                @foreach ($pertanyaan as $pertanyaanItem)
                    @if ($pertanyaanItem->halaman_id == $halaman->id)
                        <div class="bg-gray-50 border border-gray-200 p-4 mb-4 rounded-md pertanyaan"
                            data-pertanyaan-id="{{ $pertanyaanItem->id }}">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">
                                {{ json_decode($pertanyaanItem->data_pertanyaan)->teks_pertanyaan }}</h3>
                            @php
                                $dataPertanyaan = json_decode($pertanyaanItem->data_pertanyaan);
                            @endphp

                            @switch($dataPertanyaan->tipe_pertanyaan)
                                @case('radio')
                                    <p class="text-gray-600 mb-2">Opsi Jawaban:</p>
                                    @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                                        <label class="flex items-center space-x-2 mb-2">
                                            <input type="radio" name="jawaban[{{ $pertanyaanItem->id }}]"
                                                value="{{ $opsi->opsiJawaban }}" data-option-name="{{ $opsi->opsiJawaban }}"
                                                class="rounded border-gray-300 text-blue-600">
                                            <span>{{ $opsi->opsiJawaban }}</span>
                                        </label>
                                    @endforeach
                                @break

                                @case('checkbox')
                                    <p class="text-gray-600 mb-2">Opsi Jawaban:</p>
                                    @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                                        <label class="flex items-center space-x-2 mb-2">
                                            <input type="checkbox" name="jawaban[{{ $pertanyaanItem->id }}][]"
                                                value="{{ $opsi->opsiJawaban }}" data-option-name="{{ $opsi->opsiJawaban }}"
                                                class="rounded border-gray-300 text-blue-600">
                                            <span>{{ $opsi->opsiJawaban }}</span>
                                        </label>
                                    @endforeach
                                @break

                                @case('dropdown')
                                    <p class="text-gray-600 mb-2">Opsi Jawaban:</p>
                                    <select name="jawaban[{{ $pertanyaanItem->id }}]" data-option-name="dropdown"
                                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="" selected>Pilih Jawaban</option>
                                        @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                                            <option value="{{ $opsi->opsiJawaban }}">{{ $opsi->opsiJawaban }}</option>
                                        @endforeach
                                    </select>
                                @break

                                @default
                                    <p class="text-red-500">Tipe pertanyaan tidak dikenali.</p>
                            @endswitch
                        </div>
                    @endif
                @endforeach
            @else
                <p class ="text-gray-500">Tidak ada pertanyaan untuk halaman ini.</p>
            @endif
        </div>

        <!-- Navigasi Halaman -->
        <div class="flex justify-between items-center mb-4">
            @php
                $currentIndex = $halamanSemua->search($halaman);
                $prevPage = $currentIndex > 0 ? $halamanSemua[$currentIndex - 1] : null;
                $nextPage = $currentIndex < $halamanSemua->count() - 1 ? $halamanSemua[$currentIndex + 1] : null;
                $totalPages = $halamanSemua->count(); // Total halaman
                $currentPage = $currentIndex + 1; // Halaman saat ini (1-based index)
            @endphp

            <div class="info-halaman">
                <span class="text-gray-700">Halaman {{ $currentPage }} dari {{ $totalPages }}</span>
            </div>

            <div class="nav-button">
                <button class="simpan inline-block px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">Simpan</button>
                @if ($prevPage)
                    <a href="{{ route('kuesioner.alumni.page', ['slug' => $kuesioner->slug, 'halamanId' => $prevPage->id]) }}"
                        class="inline-block px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 prev-page">
                        Kembali
                    </a>
                @else
                    <span></span>
                @endif

                @if ($nextPage)
                    <a href="{{ route('kuesioner.alumni.page', ['slug' => $kuesioner->slug, 'halamanId' => $nextPage->id]) }}"
                        class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 next-page">
                        Next
                    </a>
                @endif
            </div>
        </div>
    </div>

    <button id="reset-kuesioner" class="bg-red-600 text-white px-4 py-2 rounded-lg">Reset Jawaban</button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('reset-kuesioner').addEventListener('click', function() {
                // Menghapus semua jawaban yang disimpan di Local Storage
                localStorage.clear();
                alert('Jawaban telah direset.');
            });
    
            const inputs = document.querySelectorAll('input[type="radio"], input[type="checkbox"], select');
    
            const logikas = @json(
                $pertanyaan->map(function ($pertanyaan) {
                    return json_decode($pertanyaan->logika);
                }));
    
            // console.log(logikas);
    
            // Memuat data yang sudah disimpan dari Local Storage
            const alumniKuesionerAnswers = JSON.parse(localStorage.getItem('AlumniKuesionerAnswers')) || {};
            const alumniPageAnswers = JSON.parse(localStorage.getItem('AlumniJawabanHalamanKuesioner')) || {};

            console.log(alumniKuesionerAnswers);
            console.log('halamananana: ',alumniPageAnswers);
    
            // Mengisi input dengan data yang sudah disimpan
            inputs.forEach(input => {
                const pertanyaanDiv = input.closest('.pertanyaan');
                const pertanyaanId = pertanyaanDiv.dataset.pertanyaanId;
    
                console.log(alumniKuesionerAnswers[pertanyaanId]);

                 // Pastikan alumniKuesionerAnswers[pertanyaanId] ada dan tidak kosong
    if (!alumniKuesionerAnswers[pertanyaanId]) {
        alumniKuesionerAnswers[pertanyaanId] = {}; // Inisialisasi dengan objek kosong jika belum ada
    }
                // Mengisi jawaban yang sudah disimpan
                if (alumniKuesionerAnswers[pertanyaanId]) {
                    if (input.type === 'radio' && input.value === alumniKuesionerAnswers[pertanyaanId]) {
                        input.checked = true;
                    } else if (input.type === 'checkbox') {
                        if (alumniKuesionerAnswers[pertanyaanId].includes(input.value)) {
                            input.checked = true;
                        }
                    } else if (input.tagName === 'SELECT') {
                        input.value = alumniKuesionerAnswers[pertanyaanId];
                    }
                }
    
                if (input.value) { // Pastikan nilai tidak kosong
                    displayLogic(pertanyaanDiv, input.value);
                }
    
                input.addEventListener('change', function() {
                    // Simpan jawaban ke Local Storage
                    const pertanyaanDiv = this.closest('.pertanyaan');
                    const pertanyaanId = pertanyaanDiv.dataset.pertanyaanId;
    
                    const alumniKuesionerAnswers = JSON.parse(localStorage.getItem('kuesionerAnswers')) || {};
                    // if (!alumniKuesionerAnswers[pertanyaanId]) {
                    //     alumniKuesionerAnswers[pertanyaanId] = {};
                    // }
    
                    // Simpan jawaban berdasarkan tipe input
                    if (this.type === 'radio' || this.tagName === 'SELECT') {
                        alumniKuesionerAnswers[pertanyaanId] = this.value; // Simpan langsung nilai
                    } else if (this.type === 'checkbox') {
                        const checkboxValues = alumniKuesionerAnswers[pertanyaanId] || [];
                        if (this.checked) {
                            checkboxValues.push(this.value);
                        } else {
                            const index = checkboxValues.indexOf(this.value);
                            if (index > -1) {
                                checkboxValues.splice(index, 1);
                            }
                        }
                        alumniKuesionerAnswers[pertanyaanId] = checkboxValues; // Simpan array checkbox
                    }
    
                    // Simpan kembali ke Local Storage
                    localStorage.setItem('AlumniKuesionerAnswers', JSON.stringify(alumniKuesionerAnswers));
    
                    // Display logic based on the current input value
                    displayLogic(pertanyaanDiv, this.value);
                });
            });
    
            // Function to display logic based on the selected value
            function displayLogic(pertanyaanDiv, selectedValue) {

                const pertanyaanId = pertanyaanDiv.dataset.pertanyaanId;

                // Hapus semua elemen logika yang ada
                console.log('selected value atas',selectedValue);
                
                const existingLogikas = pertanyaanDiv.querySelectorAll('.logika');
                existingLogikas.forEach(logika => logika.remove());
    
                // Temukan semua logika yang sesuai dengan nilai opsi yang dipilih
                const pertanyaanLogika = logikas.flatMap(logikaArray =>
                    logikaArray.filter(logika => {
                        const dataPertanyaan = JSON.parse(logika.data_pertanyaan);
                        return dataPertanyaan.option_name === selectedValue; // Bandingkan dengan option_name
                    })
                );
    
                console.log('selected value:', selectedValue);
                console.log(pertanyaanLogika);
    
                // Gunakan pertanyaanLogika untuk membuat elemen input
                if (pertanyaanLogika.length > 0) {

                    pertanyaanLogika.forEach(logika => {
                        console.log(logika);
                        const dataPertanyaan = JSON.parse(logika.data_pertanyaan); // Mengurai data_pertanyaan
                        const logikaDiv = document.createElement('div');
                        logikaDiv.classList.add('logika', 'mt-3', 'p-2', 'border', 'border-blue-300', 'rounded');
                         // Menambahkan atribut data-logika-id
                        logikaDiv.setAttribute('data-logika-id', logika.id); 
    
                        // Memeriksa tipe pertanyaan
                        switch (dataPertanyaan.tipe_pertanyaan) {
                            case 'text':
                                const savedTextAnswer = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${logika.id}`)) || {};
                                console.log(savedTextAnswer);
                                const inputValue = savedTextAnswer.value || ''; // Get the saved value or default to an empty string
                                // console.log(savedTextAnswer.pertanyaanId)
                                
                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    <input type="text" name="logika_jawaban[${logika.id}]" placeholder="Masukkan jawaban" class="w-full p-2 border border-gray-300 rounded" value="${inputValue}" />
                                `;
                                break;
    
                            case 'textarea':
                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    <textarea name="logika_jawaban[${pertanyaanId}]" placeholder="Masukkan jawaban" class="w-full p-2 border border-gray-300 rounded"></textarea>
                                `;
                                break;
    
                            case 'dropdown':
                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    <select name="logika_jawaban[${pertanyaanId}]" class="w-full p-2 border border-gray-300 rounded">
                                        <option value="" selected>Pilih Jawaban</option>
                                        ${dataPertanyaan.opsi_jawaban.map(opsi => `<option value="${opsi}">${opsi}</option>`).join('')}
                                    </select>
                                `;
                                break;
    
                            case 'radio':
                                const savedRadioAnswer = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${logika.id}`)) || {};
                                const radioValue = savedRadioAnswer.value || ''; // Ambil nilai yang disimpan atau default ke string kosong

                                console.log('savedRadioAnswer',savedRadioAnswer);
                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    ${dataPertanyaan.opsi_jawaban.map(opsi => `
                                        <label class="flex items-center mb-2">
                                            <input type="radio" name="logika_jawaban[${logika.id}]" value="${opsi}" class="mr-2" ${opsi === radioValue ? 'checked' : ''}>
                                            ${opsi}
                                        </label>
                                    `).join('')}
                                `;

                                break;
    
                            case 'checkbox':
                              // Ambil nilai yang disimpan dari localStorage
                                const savedCheckboxAnswer = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${logika.id}`)) || {};
                                const checkboxValues = savedCheckboxAnswer.value || []; // Ambil nilai yang disimpan atau default ke array kosong

                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    ${dataPertanyaan.opsi_jawaban.map(opsi => `
                                        <label class="flex items-center mb-2">
                                            <input type="checkbox" name="logika_jawaban[${logika.id}][]" value="${opsi}" class="mr-2" ${checkboxValues.includes(opsi) ? 'checked' : ''}>
                                            ${opsi}
                                        </label>
                                    `).join('')}
                                `;
                                break;
    
                            default:
                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">Tipe pertanyaan tidak dikenali.</h4>
                                `;
                                break;
                        }
    
                       // Tambahkan logikaDiv ke dalam pertanyaanDiv
const input = logikaDiv.querySelector('input[type="text"], textarea, select');
const inputRadio = logikaDiv.querySelectorAll(`input[type="radio"][name="logika_jawaban[${logika.id}]"]`);
const inputCheckboxes = logikaDiv.querySelectorAll('input[type="checkbox"][name="logika_jawaban[${logika.id}]"]');

if (input) {
    input.addEventListener('change', function() {
        const logikaJawaban = {
            logikaId: logika.id,
            pertanyaanId: pertanyaanId,
            value: this.value
        };
        localStorage.setItem(`logika_jawaban_alumni_${logika.id}`, JSON.stringify(logikaJawaban));
    });
} 

if (inputRadio) {
   inputRadio.forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('ajjjaa');
            const logikaJawaban = {
                logikaId: logika.id,
                pertanyaanId: pertanyaanId,
                value: this.value // Simpan nilai yang dipilih
            };
            localStorage.setItem(`logika_jawaban_alumni_${logika.id}`, JSON.stringify(logikaJawaban));
        });
         });
}

if (inputCheckboxes.length > 0) {
    inputCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const logikaJawaban = {
                logikaId: logika.id,
                pertanyaanId: pertanyaanId,
                value: Array.from(inputCheckboxes)
                    .filter(i => i.checked)
                    .map(i => i.value) // Ambil semua nilai checkbox yang tercentang
            };
            localStorage.setItem(`logika_jawaban_alumni_${logika.id}`, JSON.stringify(logikaJawaban));
        });
    });
}

                        pertanyaanDiv.appendChild(logikaDiv);
                    });
                } else {

                    const noLogikaDiv = document.createElement('div');
                    noLogikaDiv.classList.add('logika');
                    pertanyaanDiv.appendChild(noLogikaDiv);
                    
                }
            }
    
            // Load saved logic answers
        Object.keys(alumniKuesionerAnswers).forEach(pertanyaanId => {
            const pertanyaanDiv = document.querySelector (`[data-pertanyaan-id="${pertanyaanId}"]`);
            if (pertanyaanDiv) {
                const selectedValue = alumniKuesionerAnswers[pertanyaanId];
                displayLogic(pertanyaanDiv, selectedValue);
            }
        });

        // Fungsi untuk menyimpan jawaban yang terlihat ke localStorage
function saveVisibleAnswers() {
    const visibleInputs = $('.pertanyaan input:visible, .pertanyaan select:visible'); // Mengambil semua input dan select yang terlihat
    const visibleLogicInputs = $('.logika input:visible, .logika select:visible'); // Mengambil semua input dan select yang terlihat
    const answers = {};
    const logicAnswers = {}; // Objek untuk menyimpan jawaban logika

    // Mengambil jawaban dari pertanyaan
    visibleInputs.each(function() {
        const pertanyaanId = $(this).closest('.pertanyaan').data('pertanyaan-id'); // Mengambil ID pertanyaan

        if ($(this).is(':radio')) {
            if ($(this).is(':checked')) {
                answers[pertanyaanId] = $(this).val(); // Simpan nilai radio yang terpilih
            }
        } else if ($(this).is(':checkbox')) {
            if (!answers[pertanyaanId]) {
                answers[pertanyaanId] = [];
            }
            if ($(this).is(':checked')) {
                answers[pertanyaanId].push($(this).val()); // Simpan nilai checkbox yang tercentang
            }
        } else {
            answers[pertanyaanId] = $(this).val(); // Simpan nilai input lainnya
        }
    });

    // Mengambil jawaban dari logika
    visibleLogicInputs.each(function() {
        const logikaId = $(this).closest('.logika').data('logika-id'); // Mengambil ID logika

        if ($(this).is(':radio')) {
            if ($(this).is(':checked')) {
                logicAnswers[logikaId] = $(this).val(); // Simpan nilai radio yang terpilih
            }
        } else if ($(this).is(':checkbox')) {
            if (!logicAnswers[logikaId]) {
                logicAnswers[logikaId] = [];
            }
            if ($(this).is(':checked')) {
                logicAnswers[logikaId].push($(this).val()); // Simpan nilai checkbox yang tercentang
            }
        } else {
            logicAnswers[logikaId] = $(this).val(); // Simpan nilai input lainnya
        }
    });

    // Ambil data yang sudah ada di localStorage
    const existingHalamanAnswers = JSON.parse(localStorage.getItem('AlumniJawabanHalamanKuesioner')) || {};

    // Gabungkan jawaban baru dengan jawaban yang sudah ada
    Object.assign(existingHalamanAnswers, answers);
    existingHalamanAnswers['logika'] = logicAnswers; // Menyimpan jawaban logika terpisah

    // Simpan kembali ke localStorage
    localStorage.setItem('AlumniJawabanHalamanKuesioner', JSON.stringify(existingHalamanAnswers));
}
   // Event listener untuk tombol Simpan
$('.nav-button .simpan').on('click', function(event) {
    event.preventDefault(); // Mencegah aksi default tombol jika diperlukan
    saveVisibleAnswers(); // Simpan jawaban ke localStorage

    // Ambil data dari localStorage
    const existingHalamanAnswers = JSON.parse(localStorage.getItem('AlumniJawabanHalamanKuesioner')) || {};

    console.log(existingHalamanAnswers);
    // Kirim data ke server menggunakan AJAX
    $.ajax({
        url: '/kuesioner/submit', // Ganti dengan URL endpoint Anda
        method: 'POST',
        // type: 'json',
        data: {
            jawaban: existingHalamanAnswers,
            _token: $('meta[name="csrf-token"]').attr('content') // Jika Anda menggunakan CSRF token
        },
        success: function(response) {
            // Tindakan setelah berhasil menyimpan
            alert('Jawaban berhasil disimpan!');
            // Anda bisa melakukan redirect atau tindakan lain di sini
        },
        error: function(xhr) {
    const response = JSON.parse(xhr.responseText);
    if (response.error) {
        alert(response.error + (response.details ? ': ' + response.details : ''));
    } else {
        alert('Terjadi kesalahan yang tidak diketahui.');
    }
}

    });
});
    // Event listener untuk tombol Next
    $('.nav-button .next-page').on('click', function(event) {
        saveVisibleAnswers();
    });

    // Event listener untuk tombol Kembali
    $('.nav-button .prev-page').on('click', function(event) {
        saveVisibleAnswers();
    });
        });
    </script>
@endsection
