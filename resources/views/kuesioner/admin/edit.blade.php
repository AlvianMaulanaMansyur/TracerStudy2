@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Kuesioner</h1>

        <div class="bg-white shadow-md rounded-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold">{{ htmlspecialchars($kuesioner->judul_kuesioner) }}</h2>
            </div>
            <div class="px-6 py-4">
                <form id="kuesionerForm" method="POST" action="{{ route('kuesioner.update', $kuesioner->id) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="judul_kuesioner" class="block text-sm font-medium text-gray-700">Judul Kuesioner</label>
                        <input type="text" name="judul_kuesioner" id="judul_kuesioner"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('judul_kuesioner', $kuesioner->judul_kuesioner) }}" required maxlength="255">
                    </div>

                    <h3 class="text-lg font-semibold mb-2">Pertanyaan:</h3>
                    <div id="questions-section">
                        <!-- Template for questions (dynamically added) -->
                        <div id="question-template" class="hidden">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                                <input type="text" name="questions[][teks_pertanyaan]"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan teks pertanyaan" required maxlength="255">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Tipe Pertanyaan</label>
                                <select name="questions[][tipe_pertanyaan]"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 question-type"
                                    required>
                                    <option value="">Pilih Tipe Pertanyaan</option>
                                    <option value="text">Teks</option>
                                    <option value="checkbox">Pilihan Ganda</option>
                                </select>
                            </div>
                            <div class="options-group hidden mb-4">
                                <label class="block text-sm font-medium text-gray-700">Opsi Jawaban</label>
                                <input type="text" name="questions[][opsi_jawaban][]"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 mb-2"
                                    placeholder="Masukkan opsi jawaban" maxlength="255">
                                <button type="button"
                                    class="add-option bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">Tambah
                                    Opsi</button>
                            </div>
                            <button type="button"
                                class="remove-question bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus
                                Pertanyaan</button <hr class="my-4">
                        </div>

                        @foreach ($kuesioner->pertanyaan as $pertanyaan)
                            @php
                                $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan);
                            @endphp
                            <div class="mb-4 question-group">
                                <label
                                    class="block text-sm font-medium text-gray-700">{{ htmlspecialchars($dataPertanyaan->pertanyaan) }}</label>
                                <input type="text" name="questions[][teks_pertanyaan]"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('questions.' . $loop->index . '.teks_pertanyaan', $dataPertanyaan->pertanyaan) }}"
                                    required maxlength="255">
                                <select name="questions[][tipe_pertanyaan]"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 question-type"
                                    required>
                                    <option value="text"
                                        {{ $dataPertanyaan->tipe_pertanyaan === 'text' ? 'selected' : '' }}>Teks</option>
                                    <option value="checkbox"
                                        {{ $dataPertanyaan->tipe_pertanyaan === 'checkbox' ? 'selected' : '' }}>
                                        Pilihan Ganda</option>
                                    <option value="radio"
                                        {{ $dataPertanyaan->tipe_pertanyaan === 'radio' ? 'selected' : '' }}>
                                        Radio</option>
                                </select>
                                <div class="options-group mb-4">
                                    @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                                        <input type="text" name="questions[][opsi_jawaban][]"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 mb-2"
                                            value="{{ old('questions.' . $loop->parent->index . '.opsi_jawaban.' . $loop->index, $opsi) }}"
                                            placeholder="Masukkan opsi jawaban" maxlength="255">
                                    @endforeach
                                    <button type="button"
                                        class="add-option bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">Tambah
                                        Opsi</button>
                                </div>
                                <button type="button"
                                    class="remove-question bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus
                                    Pertanyaan</button>
                                <hr class="my-4">
                            </div>
                        @endforeach
                    </div>

                    <!-- Button to add questions -->
                    <button type="button" id="add-question"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mb-3">Tambah
                        Pertanyaan</button>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Perbarui
                        Kuesioner</button>
                </form>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {
            const questionSection = $('#questions-section');
            const questionTemplate = $('#question-template').clone().removeClass('hidden');

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
            questionSection.on('change', '.question-type', function() {
                updateOptionVisibility($(this));
            });

            // Inisialisasi visibilitas opsi untuk tipe pertanyaan yang sudah ada
            $('.question-type').each(function() {
                updateOptionVisibility($(this));
            });

            $('#add-question').on('click', function() {
                const newQuestion = questionTemplate.clone();
                newQuestion.find('input[name="questions[][teks_pertanyaan]"]').val('');
                newQuestion.find('select[name="questions[][tipe_pertanyaan]"]').val('');
                newQuestion.find('.options-group').addClass('hidden');
                newQuestion.find('input[name="questions[][opsi_jawaban][]"]').remove();

                // Event untuk mengubah tipe pertanyaan
                newQuestion.find('.question-type').on('change', function() {
                    const optionsGroup = newQuestion.find('.options-group');
                    optionsGroup.toggleClass('hidden', this.value !== 'checkbox' && this.value !==
                        'radio');
                });

                // Event untuk menambahkan opsi
                newQuestion.find('.add-option').on('click', function() {
                    const optionsGroup = newQuestion.find('.options-group');
                    const optionInput = $('<input>', {
                        type: 'text',
                        name: 'questions[][opsi_jawaban][]',
                        class: 'mt-1 block w-full border border-gray-300 rounded-md shadow-sm mb-2',
                        placeholder: 'Masukkan opsi jawaban'
                    });
                    optionsGroup.append(optionInput);
                });

                // Event untuk menghapus pertanyaan
                newQuestion.find('.remove-question').on('click', function() {
                    newQuestion.remove();
                });

                questionSection.append(newQuestion);
            });

            questionSection.on('click', '.add-option', function(event) {
                const optionsGroup = $(this).closest('.options-group');
                const optionInput = $('<input>', {
                    type: 'text',
                    name: 'questions[][opsi_jawaban][]',
                    class: 'mt-1 block w-full border border-gray-300 rounded-md shadow-sm mb-2',
                    placeholder: 'Masukkan opsi jawaban'
                });
                optionsGroup.append(optionInput);
            });

            questionSection.on('click', '.remove-question', function(event) {
                const questionGroup = $(this).closest('.question-group');
                if (questionGroup) {
                    questionGroup.remove(); // Hapus grup pertanyaan
                }
            });

            $('#kuesionerForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir default

                let formData = {
                    judul_kuesioner: $('#judul_kuesioner').val().trim(),
                    questions: []
                };

                const questions = $('input[name="questions[][teks_pertanyaan]"]');
                const types = $('select[name="questions[][tipe_pertanyaan]"]');
                const optionsGroups = $('.options-group');

                questions.each(function(index) {
                    const input = $(this);
                    if (input.val().trim()) {
                        const question = {
                            teks_pertanyaan: input.val().trim(),
                            tipe_pertanyaan: types.eq(index).val(),
                            opsi_jawaban: []
                        };
                        const options = optionsGroups.eq(index).find(
                            'input[name="questions[][opsi_jawaban][]"]');

                        if (question.tipe_pertanyaan === 'checkbox' || question.tipe_pertanyaan ===
                            'radio') {
                            // Jika tipe pertanyaan adalah 'checkbox' atau 'radio', ambil opsi jawaban
                            options.each(function() {
                                const optionInput = $(this);
                                if (optionInput.val().trim()) {
                                    question.opsi_jawaban.push(optionInput.val());
                                }
                            });
                        } else if (question.tipe_pertanyaan === 'text') {
                            // Jika tipe pertanyaan adalah 'text', tidak ada opsi jawaban yang perlu ditambahkan
                            question.opsi_jawaban = []; // Pastikan opsi jawaban kosong
                        }

                        formData.questions.push(question);
                    }
                });

                console.log('Data yang akan dikirim:', formData); // Debugging

                const kuesionerId = {{ $kuesioner->id }};
                $.ajax({
                    url: `/api/kuesioner/${encodeURIComponent(kuesionerId)}`,
                    method: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data) {
                        if (data.message) {
                            alert(data.message);
                            // Reset form atau lakukan tindakan lain setelah berhasil
                            $('#kuesionerForm')[0].reset();
                        } else {
                            console.error('Terjadi kesalahan:', data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endsection
