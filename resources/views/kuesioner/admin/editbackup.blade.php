@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Kuesioner</h1>

        <form id="kuesionerForm" novalidate>
            @csrf

            <!-- Flex container for buttons and questions -->
            <div class="flex">
                <!-- Buttons Section -->
                <div class="w-1/4 pr-4 sticky top-0 bg-white z-10">
                    <div class="sticky top-0 bg-white z-10 p-4">
                        <h3 class="text-lg font-semibold mb-2">Tambahkan Pertanyaan</h3>
                        <button type="button" id="add-text-question"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mr-2">Tambah
                            Teks</button>
                        <button type="button" id="add-checkbox-question"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded mr-2">Tambah
                            Checkbox</button>
                        <button type="button" id="add-radio-question"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded">Tambah
                            Radio</button>
                        <button type="button" id="add-page"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded mt-4">Tambah
                            Halaman</button>
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                                    Simpan Kuesioner
                                </button>
                            </div>
                    </div>
                </div>

                <!-- Questions Section -->
                <div id="questions-section" class="w-3/4">
                    <div class="mb-4">
                        <label for="judul_kuesioner" class="block text-sm font-medium text-gray-700">Judul Kuesioner</label>
                        <input type="text" name="judul_kuesioner" id="judul_kuesioner"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                            required value="{{ $kuesioner->judul_kuesioner }}">
                    </div>
                    <div id="page-template" class="hidden">
                        <div class="page-block mb-4">
                            <h2 class="text-lg font-semibold">Halaman <span class="page-number"></span></h2>
                            <div class="questions-container"></div>
                            <button type="button"
                                class="btn btn-danger remove-page bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus
                                Halaman</button>
                            <hr class="my-4">
                        </div>
                    </div>
                    <!-- Template for questions (dynamically added) -->
                    <div id="question-template" class="hidden">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                            <input type="text" name="questions[][teks_pertanyaan]"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan teks pertanyaan" required>
                            <div class="text-red-500 text-sm mt-1 hidden">Teks pertanyaan tidak boleh kosong.</div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tipe Pertanyaan</label>
                            <select name="questions[][tipe_pertanyaan]"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 question-type"
                                required>
                                <option value="text">Teks</option>
                                <option value="checkbox">Pilihan Ganda</option>
                                <option value="radio">Pilihan Radio</option>
                            </select>
                        </div>

                        <!-- Options for multiple choice (only show when "checkbox" is selected) -->
                        <div class="options-group hidden mb-4">
                            <label class="block text-sm font-medium text-gray-700">Opsi Jawaban</label>
                            <input type="text" name="questions[][opsi_jawaban][]"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 mb-2"
                                placeholder="Masukkan opsi jawaban">
                            <button type="button"
                                class="btn btn-secondary add-option bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">Tambah
                                Opsi</button>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Halaman</label>
                            <input type="number" name="questions[][halaman]"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nomor halaman" required>
                        </div>

                        <button type="button"
                            class="btn btn-danger remove-question bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus
                            Pertanyaan</button>
                        <hr class="my-4">
                    </div>

                    <!-- Section for dynamic questions will be appended here -->
                    @foreach ($kuesioner->pertanyaan as $pertanyaan)
                        @php
                            // Decode JSON dari kolom data_pertanyaan
                            $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan);
                        @endphp
                        <div class="page-block mb-4">
                            <h2 class="text-lg font-semibold">Halaman <span
                                    class="page-number">{{ $dataPertanyaan->halaman }}</span></h2>
                            <div class="questions-container">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                                    <input type="text" name="questions[][teks_pertanyaan]"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ $dataPertanyaan->pertanyaan }}" required>
                                    <div class="text-red-500 text-sm mt-1 hidden">Teks pertanyaan tidak boleh kosong.</div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Tipe Pertanyaan</label>
                                    <select name="questions[][tipe_pertanyaan]"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 question-type"
                                        required>
                                        <option value="text"
                                            {{ $dataPertanyaan->tipe_pertanyaan == 'text' ? 'selected' : '' }}>Teks</option>
                                        <option value="checkbox"
                                            {{ $dataPertanyaan->tipe_pertanyaan == 'checkbox' ? 'selected' : '' }}>Pilihan
                                            Ganda</option>
                                        <option value="radio"
                                            {{ $dataPertanyaan->tipe_pertanyaan == 'radio' ? 'selected' : '' }}>Pilihan
                                            Radio</option>
                                    </select>
                                </div>

                                <div
                                    class="options-group mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Opsi Jawaban</label>
                                    @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                                        <input type="text" name="questions[][opsi_jawaban][]"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 mb-2"
                                            value="{{ $opsi }}" placeholder="Masukkan opsi jawaban">
                                    @endforeach
                                    <button type="button"
                                        class="btn btn-secondary add-option bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">Tambah
                                        Opsi</button>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Halaman</label>
                                    <input type="number" name="questions[][halaman]"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ $dataPertanyaan->halaman }}" required>
                                </div>

                                <button type="button"
                                    class="btn btn-danger remove-question bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus
                                    Pertanyaan</button>
                                <hr class="my-4">
                            </div>
                        </div>
                    @endforeach
                </div>
                
            </div>
        </form>
    </div>
    <script type="module">
        $(document).ready(function() {
            const $questionSection = $('#questions-section');
            const $questionTemplate = $('#question-template').clone().removeClass('hidden');
            const $pageTemplate = $('#page-template').clone().removeClass('hidden');
            let currentPage = {{ $kuesioner->pertanyaan->data_pertanyaan->max('halaman') ?? 1 }}; // Menyimpan nomor halaman saat ini
            console.log(currentPage);

            // Fungsi untuk mengatur visibilitas opsi berdasarkan tipe pertanyaan
            function updateOptionVisibility(selectElement) {
                const questionGroup = selectElement.closest('.question-group') || selectElement.closest('#question-template');
                const optionsGroup = questionGroup.find('.options-group');
                if (selectElement.val() === 'checkbox' || selectElement.val() === 'radio') {
                    optionsGroup.removeClass('hidden');
                } else {
                    optionsGroup.addClass('hidden');
                }
            }

            // Event listener global untuk menangani perubahan tipe pertanyaan
            $questionSection.on('change', '.question-type', function() {
                updateOptionVisibility($(this));
            });

            // Inisialisasi visibilitas opsi untuk tipe pertanyaan yang sudah ada
            $('.question-type').each(function() {
                updateOptionVisibility($(this));
            });
            // Fungsi untuk menambahkan pertanyaan baru
            function addQuestion(pageContainer, type = '') {
                const $newQuestion = $questionTemplate.clone();
                $newQuestion.find('input[name="questions[][teks_pertanyaan]"]').val('');
                $newQuestion.find('select[name="questions[][tipe_pertanyaan]"]').val(type);
                $newQuestion.find('.options-group').toggleClass('hidden', type !== 'checkbox' && type !== 'radio');
                $newQuestion.find('input[name="questions[][opsi_jawaban][]"]').remove();
                $newQuestion.find('input[name="questions[][halaman]"]').val(currentPage); // Set halaman

                // Event untuk mengubah tipe pertanyaan
                $newQuestion.find('.question-type').on('change', function() {
                    const $optionsGroup = $newQuestion.find('.options-group');
                    const selectedType = $(this).val();
                    $optionsGroup.toggleClass('hidden', !['checkbox', 'radio'].includes(selectedType));
                });

                // Event untuk menambahkan opsi
                $newQuestion.find('.add-option').on('click', function() {
                    const $input = $('<input>', {
                        type: 'text',
                        name: 'questions[][opsi_jawaban][]',
                        class: 'mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 mb-2',
                        placeholder: 'Masukkan opsi jawaban',
                    });
                    $newQuestion.find('.options-group').prepend($input);
                });

                // Event untuk menghapus pertanyaan
                $newQuestion.find('.remove-question').on('click', function() {
                    $newQuestion.remove();
                });

                pageContainer.find('.questions-container').append($newQuestion);
            }

            // Fungsi untuk menambahkan halaman baru
            function addPage() {
                const $newPage = $pageTemplate.clone();
                $newPage.find('.page-number').text(currentPage);
                $newPage.find('.remove-page').on('click', function() {
                    $newPage.remove();
                });

                // Tambahkan tombol untuk menambahkan pertanyaan di halaman ini
                const $addQuestionButton = $('<button>', {
                    type: 'button',
                    class: 'btn btn-primary add-question bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mt-4',
                    text: 'Tambah Pertanyaan',
                });

                // Event untuk menambahkan pertanyaan di halaman ini
                $addQuestionButton.on('click', function() {
                    addQuestion($newPage, 'text'); // Anda bisa menyesuaikan tipe pertanyaan
                });

                $newPage.append($addQuestionButton); // Tambahkan tombol ke halaman
                $questionSection.append($newPage);
                addQuestion($newPage, 'text'); // Tambahkan pertanyaan pertama di halaman baru
                currentPage++; // Increment nomor halaman
            }

            // Event untuk tombol tambah pertanyaan berdasarkan tipe
            $('#add-text-question').on('click', function() {
                const $lastPage = $('.page-block').last();
                addQuestion($lastPage, 'text');
            });

            $('#add-checkbox-question').on('click', function() {
                const $lastPage = $('.page-block').last();
                addQuestion($lastPage, 'checkbox');
            });

            $('#add-radio-question').on('click', function() {
                const $lastPage = $('.page-block').last();
                addQuestion($lastPage, 'radio');
            });

            // Event untuk tombol tambah halaman
            $('#add-page').on('click', function() {
                addPage(); // Tambahkan halaman baru
            });

            $questionSection.on('click', '.add-option', function(event) {
                const optionsGroup = $(this).closest('.options-group');
                const optionInput = $('<input>', {
                    type: 'text',
                    name: 'questions[][opsi_jawaban][]',
                    class: 'mt-1 block w-full border border-gray-300 rounded-md shadow-sm mb-2',
                    placeholder: 'Masukkan opsi jawaban'
                });
                optionsGroup.append(optionInput);
            });

            $questionSection.on('click', '.remove-question', function(event) {
                const questionGroup = $(this).closest('.question-container');
                if (questionGroup) {
                    questionGroup.remove(); // Hapus grup pertanyaan
                }
            });

            // Cegah pengiriman form jika ada input yang tidak valid
            $('#kuesionerForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir default

                let formData = {
                    judul_kuesioner: $('#judul_kuesioner').val(),
                    questions: [],
                };

                $('.page-block').each(function() {
                    const $page = $(this);
                    const pageNumber = $page.find('.page-number').text();
                    const $questions = $page.find('input[name="questions[][teks_pertanyaan]"]');
                    const $types = $page.find('select[name="questions[][tipe_pertanyaan]"]');
                    const $optionsGroups = $page.find('.options-group');

                    $questions.each(function(index) {
                        const teksPertanyaan = $(this).val().trim();
                        if (teksPertanyaan) {
                            const question = {
                                teks_pertanyaan: teksPertanyaan,
                                tipe_pertanyaan: $types.eq(index).val(),
                                opsi_jawaban: [],
                                halaman: pageNumber // Set halaman dari nomor halaman
                            };

                            $optionsGroups.eq(index).find(
                                    'input[name="questions[][opsi_jawaban][]"]')
                                .each(function() {
                                    const opsiJawaban = $(this).val().trim();
                                    if (opsiJawaban) {
                                        question.opsi_jawaban.push(opsiJawaban);
                                    }
                                });

                            formData.questions.push(question);
                        }
                    });
                });

                // Kirim data ke API
                $.ajax({
                    url: '/api/kuesioner/' + {{ $kuesioner->id }},
                    method: 'PUT',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content'), // Jika diperlukan
                    },
                    data: JSON.stringify(formData),
                    success: function(data) {
                        if (data.message) {
                            alert(data.message);
                            // Reset form atau lakukan tindakan lain setelah berhasil
                            $('#kuesionerForm')[0].reset();
                        } else {
                            console.error('Terjadi kesalahan:', data);
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    },
                });
            });
        });
    </script>
@endsection
