@extends('layouts.alumniDashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex gap-2 mb-4">
            <a href="{{ route('kuesioner.alumni.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Kembali ke
                Kuesioner</a>
        </div>
        {{-- {{ $kuesioner }} --}}
        <div class="bg-white shadow rounded-lg p-6 mb-6" id="halaman-{{ $halaman->id }}">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $halaman->judul_halaman }}</h2>

            @if ($sortedQuestions->isNotEmpty())
    @foreach ($sortedQuestions as $pertanyaanItem)
        @if ($pertanyaanItem->halaman_id == $halaman->id)
            <div class="bg-gray-50 border border-gray-200 p-4 mb-4 rounded-md pertanyaan"
                data-pertanyaan-id="{{ $pertanyaanItem->id }}">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">
                    {{ json_decode($pertanyaanItem->data_pertanyaan)->teks_pertanyaan }}
                    @php
                        $dataPertanyaan = json_decode($pertanyaanItem->data_pertanyaan);
                    @endphp
                    @if ($dataPertanyaan->is_required)
                        <span class="text-red-500">*</span> <!-- Menampilkan asterisk jika required -->
                    @endif
                </h3>

                @switch($dataPertanyaan->tipe_pertanyaan)
                    @case('text')
                        <p class="text-gray-600 mb-2">Jawaban:</p>
                        <input type="text" name="jawaban[{{ $pertanyaanItem->id }}]"
                            class="border rounded p-2 w-full" placeholder="Masukkan jawaban Anda"
                            @if ($dataPertanyaan->is_required) required @endif>
                    @break
                    @case('radio')
                        <p class="text-gray-600 mb-2">Opsi Jawaban:</p>
                        @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                            <label class="flex items-center space-x-2 mb-2">
                                <input type="radio" name="jawaban[{{ $pertanyaanItem->id }}]"
                                    value="{{ $opsi->opsiJawaban }}" data-option-name="{{ $opsi->opsiJawaban }}"
                                    class="rounded border-gray-300 text-blue-600"
                                    @if ($dataPertanyaan->is_required) required @endif>
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
                                    class="rounded border-gray-300 text-blue-600"
                                    @if ($dataPertanyaan->is_required) required @endif>
                                <span>{{ $opsi->opsiJawaban }}</span>
                            </label>
                        @endforeach
                    @break

                    @case('dropdown')
                        <p class="text-gray-600 mb-2">Opsi Jawaban:</p>
                        <select name="jawaban[{{ $pertanyaanItem->id }}]" data-option-name="dropdown"
                            class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            @if ($dataPertanyaan->is_required) required @endif>
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

                @if ($isHalamanTerakhir)
                <button class="simpan inline-block px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">Simpan</button>
            @endif
            </div>
        </div>
    </div>

    <button id="reset-kuesioner" class="bg-red-600 text-white px-4 py-2 rounded-lg">Reset Jawaban</button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kuesionerId = @json($kuesioner->id);
            const halamanId = @json($halaman->id);
            console.log(kuesionerId);
            console.log(halamanId);

            document.getElementById('reset-kuesioner').addEventListener('click', function() {
                // Menghapus semua jawaban yang disimpan di Local Storage
                localStorage.clear();
                alert('Jawaban telah direset.');
            });

            const inputs = document.querySelectorAll('input[type="radio"], input[type="checkbox"], select');

            const logikas = @json(
                $sortedQuestions->map(function ($pertanyaan) {
                    return json_decode($pertanyaan->logika);
                }));

            // console.log(logikas);

            // Memuat data yang sudah disimpan dari Local Storage
            const alumniKuesionerAnswers = JSON.parse(localStorage.getItem('AlumniKuesionerAnswers[' + kuesionerId + ']')) || {};
            const alumniPageAnswers = JSON.parse(localStorage.getItem('AlumniJawabanHalamanKuesioner[' + kuesionerId + ']')) || {};

            console.log(alumniKuesionerAnswers);
            console.log('halamananana: ',alumniPageAnswers);


            // Mengisi input dengan data yang sudah disimpan
            inputs.forEach(input => {
                const pertanyaanDiv = input.closest('.pertanyaan');
                const pertanyaanId = pertanyaanDiv.dataset.pertanyaanId;
                const allLogikaJawaban = JSON.parse(localStorage.getItem('all_logika_jawaban_alumni')) || {};
                console.log('semua logika: ',allLogikaJawaban);

                // const logikaalumni = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`)) || {};
                // console.log('logika alumni',logikaalumni);
                console.log('pertanyaan id:', pertanyaanId);

                console.log(alumniKuesionerAnswers[pertanyaanId]);

                 // Pastikan alumniKuesionerAnswers[pertanyaanId] ada dan tidak kosong
    if (!alumniKuesionerAnswers[pertanyaanId]) {
        console.log('alalalalal');
        alumniKuesionerAnswers[pertanyaanId] = {}; // Inisialisasi dengan objek kosong jika belum ada
    }
    if (alumniKuesionerAnswers[pertanyaanId]) {
        if (input.type === 'radio' && input.value === alumniKuesionerAnswers[pertanyaanId]) {
            input.checked = true;
        } else if (input.type === 'checkbox' && alumniKuesionerAnswers[pertanyaanId]) {
            // Cek apakah alumniKuesionerAnswers[pertanyaanId] adalah array
            if (Array.isArray(alumniKuesionerAnswers[pertanyaanId])) {
                // Set checkbox checked jika nilai checkbox ada dalam array
                input.checked = alumniKuesionerAnswers[pertanyaanId].includes(input.value);
            }
        } else if (input.tagName === 'SELECT' && input.value === alumniKuesionerAnswers[pertanyaanId]) {
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

                    const alumniKuesionerAnswers = JSON.parse(localStorage.getItem('AlumniKuesionerAnswers[' + kuesionerId + ']')) || {};
                    if (!alumniKuesionerAnswers[pertanyaanId]) {
                        alumniKuesionerAnswers[pertanyaanId] = {};
                    }

                    // Simpan jawaban berdasarkan tipe input
if (!this || !this.type || !this.tagName) {
    console.error('Elemen input tidak valid:', this);
    return;
}

if (this.type === 'radio' || this.tagName === 'SELECT') {
    alumniKuesionerAnswers[pertanyaanId] = this.value; // Simpan langsung nilai
} else if (this.type === 'checkbox') {
    // Jika nilai sebelumnya adalah objek, ubah menjadi array kosong
    let checkboxValues = alumniKuesionerAnswers[pertanyaanId];
    console.log('jsdjfjfjf');

    if (!Array.isArray(checkboxValues)) {
        console.warn(`Nilai sebelumnya bukan array. Mengatur ulang menjadi array kosong.`, checkboxValues);
        checkboxValues = []; // Inisialisasi ulang sebagai array
    }

    console.log('Checkbox values sebelum:', checkboxValues);

    // Tambahkan atau hapus nilai berdasarkan apakah checkbox dicentang
    if (this.checked && !checkboxValues.includes(this.value)) {
        checkboxValues.push(this.value);
    } else if (!this.checked) {
        const index = checkboxValues.indexOf(this.value);
        if (index > -1) {
            checkboxValues.splice(index, 1);
        }
    }

    console.log('Checkbox values setelah:', checkboxValues);

    // Simpan kembali nilai sebagai array
    alumniKuesionerAnswers[pertanyaanId] = checkboxValues;
    console.log('checkbox values', alumniKuesionerAnswers[pertanyaanId]);
}

                    // Simpan kembali ke Local Storage
                    localStorage.setItem('AlumniKuesionerAnswers[' + kuesionerId + ']', JSON.stringify(alumniKuesionerAnswers));

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
                                const savedTextAnswer = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`)) || {};
                                console.log(savedTextAnswer);
                                const inputValue = savedTextAnswer.value || ''; // Get the saved value or default to an empty string

                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    <input type="text" name="logika_jawaban[${logika.id}]" placeholder="Masukkan jawaban" class="w-full p-2 border border-gray-300 rounded" value="${inputValue}" />
                                `;
                                break;

                            case 'textarea':
                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    <textarea name="logika_jawaban[${logika.id}]" placeholder="Masukkan jawaban" class="w-full p-2 border border-gray-300 rounded"></textarea>
                                `;
                                break;

                            case 'dropdown':
                                logikaDiv.innerHTML = `
                                    <h4 class="font-medium text-gray-700 mb-2">${dataPertanyaan.teks_pertanyaan}</h4>
                                    <select name="logika_jawaban[${logika.id}]" class="w-full p-2 border border-gray-300 rounded">
                                        <option value="" selected>Pilih Jawaban</option>
                                        ${dataPertanyaan.opsi_jawaban.map(opsi => `<option value="${opsi}">${opsi}</option>`).join('')}
                                    </select>
                                `;
                                break;

                            case 'radio':
                                const savedRadioAnswer = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`)) || {};
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
                                const savedCheckboxAnswer = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`)) || {};
                                const checkboxValues = savedCheckboxAnswer.value || []; // Ambil nilai yang disimpan atau default ke array kosong
                                console.log(savedCheckboxAnswer);

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
const inputCheckboxes = logikaDiv.querySelectorAll(`input[type="checkbox"][name="logika_jawaban[${logika.id}]\\[\\]"]`);

console.log('input checkbox',inputCheckboxes);

if (input) {
    input.addEventListener('change', function() {
        const logikaJawaban = {
            logikaId: logika.id,
            // pertanyaanId: pertanyaanId,
            value: this.value
        };
            localStorage.setItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`, JSON.stringify(logikaJawaban));
            saveLogikaJawaban(pertanyaanId, logikaJawaban);
            console.log('ajsdfsdfdf');
    });
}

if (inputRadio) {
   inputRadio.forEach(radio => {
        radio.addEventListener('change', function() {
            const logikaJawaban = {
                logikaId: logika.id,
                // pertanyaanId: pertanyaanId,
                value: this.value // Simpan nilai yang dipilih
            };
                localStorage.setItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`, JSON.stringify(logikaJawaban));
                saveLogikaJawaban(pertanyaanId, logikaJawaban);
        });
    });
}

console.log('halololo',inputCheckboxes.length);
// console.log(document.body.innerHTML);

if (inputCheckboxes.length > 0) {
    inputCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const logikaJawaban = {
                logikaId: logika.id,
                // pertanyaanId: pertanyaanId,
                value: Array.from(inputCheckboxes)
                    .filter(i => i.checked)
                    .map(i => i.value) // Ambil semua nilai checkbox yang tercentang
            };
            localStorage.setItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`, JSON.stringify(logikaJawaban));
            saveLogikaJawaban(pertanyaanId, logikaJawaban);
        });
    });
}

// Function to save logic answers in a separate storage
function saveLogikaJawaban(pertanyaanId, logikaJawaban) {
    const allLogikaJawaban = JSON.parse(localStorage.getItem('all_logika_jawaban_alumni')) || {};
    if (!allLogikaJawaban[pertanyaanId]) {
        allLogikaJawaban[pertanyaanId] = {};
    }
    allLogikaJawaban[pertanyaanId][logikaJawaban.logikaId] = logikaJawaban;
    localStorage.setItem('all_logika_jawaban_alumni', JSON.stringify(allLogikaJawaban));
}

const logikaalumni = JSON.parse(localStorage.getItem(`logika_jawaban_alumni_${pertanyaanId}_${logika.id}`)) || {};
console.log('logika alumni',logikaalumni);

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

function saveVisibleAnswers() {
    // Mengambil semua input dan select yang terlihat, kecuali yang ada di dalam .logika
    const visibleInputs = $('.pertanyaan input:visible, .pertanyaan select:visible').not('.logika input:visible, .logika select:visible');
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
$('.logika').each(function() {
    const logikaId = $(this).data('logika-id'); // Mengambil ID logika
    const pertanyaanId = $(this).closest('.pertanyaan').data('pertanyaan-id'); // Mengambil ID pertanyaan terkait

    // Ambil nilai dari input logika
    const logikaValues = [];

    // Ambil nilai dari checkbox yang tercentang
    const checkedCheckboxes = $(this).find('input[type="checkbox"]:checked').map(function() {
        return $(this).val(); // Mengambil nilai dari checkbox yang tercentang
    }).get(); // Mengubah hasil menjadi array

    // Ambil nilai dari radio yang terpilih
    const selectedRadio = $(this).find('input[type="radio"]:checked').val();

    // Ambil nilai dari select yang terpilih
    const selectedSelect = $(this).find('select').val();

    // Ambil nilai dari input teks
    const textInputValue = $(this).find('input[type="text"]').val(); // Ambil nilai dari input teks

    // Tambahkan nilai ke dalam array logikaValues
    if (checkedCheckboxes.length > 0) {
        logikaValues.push(...checkedCheckboxes); // Menambahkan semua nilai checkbox ke array
    }
    if (selectedRadio) {
        logikaValues.push(selectedRadio); // Menambahkan nilai radio ke array
    }
    if (selectedSelect) {
        logikaValues.push(selectedSelect); // Menambahkan nilai select ke array
    }
    if (textInputValue) {
        logikaValues.push(textInputValue); // Menambahkan nilai input teks ke array
    }

    // Hanya simpan jawaban logika jika ada jawaban untuk pertanyaan ini
    if (answers[pertanyaanId]) {
        // Hanya simpan logika jika ada nilai
        if (logikaValues.length > 0) {
            logicAnswers[logikaId] = logikaValues; // Simpan nilai logika sebagai array
        }
    }
});

    // Ambil data yang sudah ada di localStorage
    const existingHalamanAnswers = JSON.parse(localStorage.getItem('AlumniJawabanHalamanKuesioner[' + kuesionerId + ']')) || {};

    // Inisialisasi objek untuk halaman jika belum ada
    if (!existingHalamanAnswers[halamanId]) {
        existingHalamanAnswers[halamanId] = {};
    }

    existingHalamanAnswers[halamanId]['pertanyaan'] = answers; // Menyimpan jawaban pertanyaan
    existingHalamanAnswers[halamanId]['logika'] = logicAnswers; // Menyimpan jawaban logika terpisah

    // Simpan kembali ke localStorage
    localStorage.setItem('AlumniJawabanHalamanKuesioner[' + kuesionerId + ']', JSON.stringify(existingHalamanAnswers));
}

   // Event listener untuk tombol Simpan
$('.nav-button .simpan').on('click', function(event) {
    event.preventDefault(); // Mencegah aksi default tombol jika diperlukan
    saveVisibleAnswers(); // Simpan jawaban ke localStorage

    // Ambil data dari localStorage
    const existingHalamanAnswers = JSON.parse(localStorage.getItem('AlumniJawabanHalamanKuesioner[' + kuesionerId + ']')) || {};

    console.log('halo',existingHalamanAnswers);
    // Kirim data ke server menggunakan AJAX
    $.ajax({
        url: '/kuesioner/submit', // Ganti dengan URL endpoint Anda
        method: 'POST',
        data: {
            jawaban: existingHalamanAnswers,
            kuesioner_id: kuesionerId,
            _token: $('meta[name="csrf-token"]').attr('content') // Jika Anda menggunakan CSRF token
        },
        success: function(data) {
            // Tindakan setelah berhasil menyimpan
            localStorage.removeItem('AlumniJawabanHalamanKuesioner[' + kuesionerId + ']');
            localStorage.removeItem('AlumniKuesionerAnswers[' + kuesionerId + ']');

                if (data.message) {
            // Gunakan SweetAlert untuk menampilkan pesan sukses
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK',
            }).then(() => {
                // Refresh halaman setelah user klik OK
             var kuesionerUrl = "{{ route('kuesioner.alumni.finish') }}";
                window.location.href = kuesionerUrl;
            });
        } else {
            console.error('Terjadi kesalahan:', data);
        }
        },
        error: function(xhr, status, error, data) {
        console.error('Error:', error);
        // Gunakan SweetAlert untuk menampilkan pesan error
        const errorMessage = xhr.responseJSON.error
        Swal.fire({
            title: 'Error!',
            text: errorMessage,
            icon: 'error',
            confirmButtonText: 'OK',
        });
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
