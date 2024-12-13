@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Buat Kuesioner Baru</h1>

    <form id="kuesionerForm" novalidate>
        @csrf

        <!-- Flex container for buttons and questions -->
        <div class="flex">
            <!-- Buttons Section -->
            <div class="w-1/4 pr-4 sticky top-0 bg-white z-10">
                @include('kuesioner.admin.add-question-buttons')
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mt-4">Simpan
                    Kuesioner</button>
            </div>

            <!-- Questions Section -->
            {{-- <div class="w-3/4 custom-scroll overflow-y-auto max-h-[79vh]">
                <div class="flex justify-between mb-4">
                    <h2 class="text-xl font-semibold">Halaman Kuesioner</h2>
                    <button id="add-page" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">Tambah Halaman</button>
                </div>

                <div id="pages-section" class="mb-4">
                    <!-- Halaman akan ditambahkan di sini -->
                </div>
            </div> --}}
            <div id="questions-section" class="w-3/4 custom-scroll overflow-y-auto max-h-[79vh]">
                <div class="mb-4">
                    <label for="judul_kuesioner" class="block text-sm font-medium text-gray-700">Judul Kuesioner</label>
                    <input type="text" name="judul_kuesioner" id="judul_kuesioner"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                        required>
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

                    <button type="button"
                        class="btn btn-danger remove-question bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus
                        Pertanyaan</button>
                    <hr class="my-4">
                </div>

                <!-- Section for dynamic questions will be appended here -->
            </div>
        </div>
    </form>
</div>
    <script type="module">
        $(document).ready(function() {
            const $questionSection = $('#questions-section');
            const $questionTemplate = $('#question-template').clone().removeClass('hidden');

            // Fungsi untuk menambahkan pertanyaan baru
            function addQuestion(type = '') {
                const $newQuestion = $questionTemplate.clone();
                $newQuestion.find('input[name="questions[][teks_pertanyaan]"]').val('');
                $newQuestion.find('select[name="questions[][tipe_pertanyaan]"]').val(type);
                $newQuestion.find('.options-group').toggleClass('hidden', type !== 'checkbox' && type !== 'radio');
                $newQuestion.find('input[name="questions[][opsi_jawaban][]"]').remove();

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

                $questionSection.append($newQuestion);
            }

            // Event untuk tombol tambah pertanyaan berdasarkan tipe
            $('#add-text-question').on('click', function() {
                addQuestion('text');
            });

            $('#add-checkbox-question').on('click', function() {
                addQuestion('checkbox');
            });

            $('#add-radio-question').on('click', function() {
                addQuestion('radio');
            });

            $('#add-question').on('click', function() {
                addQuestion('');
            });

            // Cegah pengiriman form jika ada input yang tidak valid
            $('#kuesionerForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir default

                let formData = {
                    judul_kuesioner: $('#judul_kuesioner').val(),
                    questions: [],
                };

                const $questions = $('input[name="questions[][teks_pertanyaan]"]');
                const $types = $('select[name="questions[][tipe_pertanyaan]"]');
                const $optionsGroups = $('.options-group');

                $questions.each(function(index) {
                    const teksPertanyaan = $(this).val().trim();
                    if (teksPertanyaan) {
                        const question = {
                            teks_pertanyaan: teksPertanyaan,
                            tipe_pertanyaan: $types.eq(index).val(),
                            opsi_jawaban: [],
                        };

                        $optionsGroups.eq(index).find('input[name="questions[][opsi_jawaban][]"]')
                            .each(function() {
                                const opsiJawaban = $(this).val().trim();
                                if (opsiJawaban) {
                                    question.opsi_jawaban.push(opsiJawaban);
                                }
                            });

                        formData.questions.push(question);
                    }
                });

                // Kirim data ke API
                $.ajax({
                    url: '/api/kuesioner',
                    method: 'POST',
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
