@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Kuesioner</h1>

        <div class="card">
            <div class="card-header">
                <h2>{{ $kuesioner->judul_kuesioner }}</h2>
            </div>
            <div class="card-body">
                <form id="kuesionerForm" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="judul_kuesioner" class="form-label">Judul Kuesioner</label>
                        <input type="text" name="judul_kuesioner" class="form-control" id="judul_kuesioner"
                            value="{{ old('judul_kuesioner', $kuesioner->judul_kuesioner) }}" required>
                    </div>

                    <h3>Pertanyaan:</h3>
                    <div id="questions-section">
                        <!-- Template for questions (dynamically added) -->
                        <div id="question-template" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Pertanyaan</label>
                                <input type="text" name="questions[][teks_pertanyaan]" class="form-control"
                                    placeholder="Masukkan teks pertanyaan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe Pertanyaan</label>
                                <select name="questions[][tipe_pertanyaan]" class="form-control question-type" required>
                                    <option value="">Pilih Tipe Pertanyaan</option>
                                    <option value="text">Teks</option>
                                    <option value="multiple_choice">Pilihan Ganda</option>
                                </select>
                            </div>
                            <div class="mb-3 options-group" style="display: none;">
                                <label class="form-label">Opsi Jawaban</label>
                                <input type="text" name="questions[][opsi_jawaban][]" class="form-control mb-2"
                                    placeholder="Masukkan opsi jawaban">
                                <button type="button" class="btn btn-secondary add-option">Tambah Opsi</button>
                            </div>
                            <button type="button" class="btn btn-danger remove-question">Hapus Pertanyaan</button>
                            <hr>
                        </div>

                        @foreach ($kuesioner->pertanyaan as $pertanyaan)
                            @php
                                $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan);
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">{{ $dataPertanyaan->pertanyaan }}</label>
                                <input type="text" name="questions[][teks_pertanyaan]" class="form-control"
                                    value="{{ $dataPertanyaan->pertanyaan }}" required>
                                <select name="questions[][tipe_pertanyaan]" class="form-control question-type" required>
                                    <option value="text"
                                        {{ $dataPertanyaan->tipe_pertanyaan === 'text' ? 'selected' : '' }}>Teks</option>
                                    <option value="multiple_choice"
                                        {{ $dataPertanyaan->tipe_pertanyaan === 'multiple_choice' ? 'selected' : '' }}>
                                        Pilihan Ganda</option>
                                </select>
                                <div class="options-group"
                                    style="{{ $dataPertanyaan->tipe_pertanyaan === 'multiple_choice' ? 'display: block;' : 'display: none;' }}">
                                    @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                                        <input type="text" name="questions[][opsi_jawaban][]" class="form-control mb-2"
                                            value="{{ $opsi }}" placeholder="Masukkan opsi jawaban">
                                    @endforeach
                                    <button type="button" class="btn btn-secondary add-option border-red-400">Tambah
                                        Opsi</button>
                                </div>
                                <button type="button" class="btn btn-danger remove-question">Hapus Pertanyaan</button>
                                <hr>
                            </div>
                        @endforeach
                    </div>

                    <!-- Button to add questions -->
                    <button type="button" id="add-question" class="btn btn-secondary mb-3">Tambah Pertanyaan</button>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Perbarui Kuesioner</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionSection = document.getElementById('questions-section');
            const questionTemplate = document.getElementById('question-template').cloneNode(true);
            questionTemplate.style.display = '';

            // Function to add new question
            document.getElementById('add-question').addEventListener('click', function() {
                const newQuestion = questionTemplate.cloneNode(true);
                newQuestion.querySelector('input[name="questions[][teks_pertanyaan]"]').value = '';
                newQuestion.querySelector('select[name="questions[][tipe_pertanyaan]"]').value = '';
                newQuestion.querySelector('.options-group').style.display = 'none';
                newQuestion.querySelectorAll('input[name="questions[][opsi_jawaban][]"]').forEach(input =>
                    input.remove());

                // Event for changing question type
                newQuestion.querySelector('.question-type').addEventListener('change', function() {
                    const optionsGroup = newQuestion.querySelector('.options-group');
                    optionsGroup.style.display = this.value === 'multiple_choice' ? 'block' :
                    'none';
                    console.log('Question type changed to:', this.value);
                });

                // Event for adding options
                newQuestion.querySelector('.add-option').addEventListener('click', function() {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'questions[][opsi_jawaban][]';
                    input.classList.add('form-control', 'mb-2');
                    input.placeholder = 'Masukkan opsi jawaban';
                    newQuestion.querySelector('.options-group').insertBefore(input, this);
                    console.log('Added new option input');
                });

                // Event for removing question
                newQuestion.querySelector('.remove-question').addEventListener('click', function() {
                    newQuestion.remove();
                    console.log('Removed question');
                });

                questionSection.appendChild(newQuestion);
                console.log('Added new question');
            });

            // Prevent form submission if there are invalid inputs
            document.getElementById('kuesionerForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir default

                let formData = {
                    judul_kuesioner: document.getElementById('judul_kuesioner').value,
                    questions: []
                };

                const questions = document.querySelectorAll('input[name="questions[][teks_pertanyaan]"]');
                const types = document.querySelectorAll('select[name="questions[][tipe_pertanyaan]"]');
                const optionsGroups = document.querySelectorAll('.options-group');

                questions.forEach((input, index) => {
                    if (input.value.trim()) {
                        const question = {
                            teks_pertanyaan: input.value,
                            tipe_pertanyaan: types[index].value,
                            opsi_jawaban: []
                        };

                        const options = optionsGroups[index].querySelectorAll(
                            'input[name="questions[][opsi_jawaban][]"]');
                        options.forEach(optionInput => {
                            if (optionInput.value.trim()) {
                                question.opsi_jawaban.push(optionInput.value);
                            }
                        });

                        formData.questions.push(question);
                        console.log('Added question:', question);
                    }
                });

                const kuesionerId = {{ $kuesioner->id }};
                // Kirim data ke API
                fetch('http://tracer-study.test:8080/api/kuesioner/'+kuesionerId, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(formData),
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.text(); // Ambil respons sebagai teks
                    })
                    .then(text => {
                        console.log('Response text:', text); // Log respons teks
                        try {
                            const data = JSON.parse(text); // Coba parse JSON
                            if (data.message) {
                                alert(data.message);
                                document.getElementById('kuesionerForm').reset();
                            } else {
                                console.error('Terjadi kesalahan:', data);
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
@endsection
