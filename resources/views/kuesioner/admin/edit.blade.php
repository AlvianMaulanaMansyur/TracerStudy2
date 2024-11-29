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
                                    <option value="multiple_choice">Pilihan Ganda</option>
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
                                    <option value="multiple_choice"
                                        {{ $dataPertanyaan->tipe_pertanyaan === 'multiple_choice' ? 'selected' : '' }}>
                                        Pilihan Ganda</option>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionSection = document.getElementById('questions-section');
            const questionTemplate = document.getElementById('question-template').cloneNode(true);
            questionTemplate.classList.remove('hidden');

            // Fungsi untuk mengatur visibilitas opsi berdasarkan tipe pertanyaan
            function updateOptionVisibility(selectElement) {
                const questionGroup = selectElement.closest('.question-group') || selectElement.closest(
                    '#question-template');
                const optionsGroup = questionGroup.querySelector('.options-group');
                if (selectElement.value === 'multiple_choice') {
                    optionsGroup.classList.remove('hidden');
                } else {
                    optionsGroup.classList.add('hidden');
                }
            }

            // Event listener global untuk menangani perubahan tipe pertanyaan
            questionSection.addEventListener('change', function(event) {
                if (event.target.classList.contains('question-type')) {
                    updateOptionVisibility(event.target);
                }
            });

            const questionTypes = document.querySelectorAll('.question-type');
        questionTypes.forEach(select => {
            updateOptionVisibility(select);
        });

            document.getElementById('add-question').addEventListener('click', function() {
                const newQuestion = questionTemplate.cloneNode(true);
                newQuestion.querySelector('input[name="questions[][teks_pertanyaan]"]').value = '';
                newQuestion.querySelector('select[name="questions[][tipe_pertanyaan]"]').value = '';
                newQuestion.querySelector('.options-group').classList.add('hidden');
                newQuestion.querySelectorAll('input[name="questions[][opsi_jawaban][]"]').forEach(input =>
                    input.remove());

                // Event for changing question type
                newQuestion.querySelector('.question-type').addEventListener('change', function() {
                    const optionsGroup = newQuestion.querySelector('.options-group');
                    optionsGroup.classList.toggle('hidden', this.value !== 'multiple_choice');
                });

                // Event for adding options
                newQuestion.querySelector('.add-option').addEventListener('click', function() {
                    const optionsGroup = newQuestion.querySelector('.options-group');
                    const optionInput = document.createElement('input');
                    optionInput.type = 'text';
                    optionInput.name = `questions[][opsi_jawaban][]`;
                    optionInput.classList.add('mt-1', 'block', 'w-full', 'border',
                        'border-gray-300', 'rounded-md', 'shadow-sm', 'mb-2');
                    optionInput.placeholder = 'Masukkan opsi jawaban';
                    optionsGroup.appendChild(optionInput);
                });

                // Event for removing question
                newQuestion.querySelector('.remove-question').addEventListener('click', function() {
                    newQuestion.remove();
                });

                questionSection.appendChild(newQuestion);
            });

            questionSection.addEventListener('click', function(event) {
                if (event.target.classList.contains('add-option')) {
                    const optionsGroup = event.target.closest('.options-group');
                    const optionInput = document.createElement('input');
                    optionInput.type = 'text';
                    optionInput.name = `questions[][opsi_jawaban][]`;
                    optionInput.classList.add('mt-1', 'block', 'w-full', 'border', 'border-gray-300',
                        'rounded-md', 'shadow-sm', 'mb-2');
                    optionInput.placeholder = 'Masukkan opsi jawaban';
                    optionsGroup.appendChild(optionInput);
                }
            });

            questionSection.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-question')) {
                    const questionGroup = event.target.closest('.question-group');
                    if (questionGroup) {
                        questionGroup.remove(); // Remove the question group
                    }
                }
            });

            document.getElementById('kuesionerForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir default

                let formData = {
                    judul_kuesioner: document.getElementById('judul_kuesioner').value.trim(),
                    questions: []
                };

                const questions = document.querySelectorAll('input[name="questions[][teks_pertanyaan]"]');
                const types = document.querySelectorAll('select[name="questions[][tipe_pertanyaan]"]');
                const optionsGroups = document.querySelectorAll('.options-group');

                questions.forEach((input, index) => {
                    if (input.value.trim()) {
                        const question = {
                            teks_pertanyaan: input.value.trim(),
                            tipe_pertanyaan: types[index].value,
                            opsi_jawaban: []
                        };
                        const options = optionsGroups[index].querySelectorAll(
                            'input[name="questions[][opsi_jawaban][]"]');

                        if (question.tipe_pertanyaan === 'multiple_choice') {
                            // Jika tipe pertanyaan adalah 'multiple_choice', ambil opsi jawaban
                            options.forEach(optionInput => {
                                if (optionInput.value.trim()) {
                                    question.opsi_jawaban.push(optionInput.value);
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
                fetch(`/api/kuesioner/${encodeURIComponent(kuesionerId)}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        body: JSON.stringify(formData),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                            // Reset form atau lakukan tindakan lain setelah berhasil
                            document.getElementById('kuesionerForm').reset();
                        } else {
                            console.error('Terjadi kesalahan:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
                    });
            });
        });
    </script>
@endsection
