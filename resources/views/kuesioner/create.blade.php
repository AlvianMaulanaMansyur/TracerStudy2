@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Kuesioner Baru</h1>

    <form id="kuesionerForm" novalidate>
        @csrf

        <!-- Title of the Questionnaire -->
        <div class="form-group">
            <label for="judul_kuesioner">Judul Kuesioner</label>
            <input type="text" name="judul_kuesioner" id="judul_kuesioner" class="form-control" required>
        </div>

        <!-- Questions Section -->
        <div id="questions-section">
            <h3>Pertanyaan</h3>
            
            <!-- Template for questions (dynamically added) -->
            <div id="question-template" style="display: none;">
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <input type="text" name="questions[][teks_pertanyaan]" class="form-control" placeholder="Masukkan teks pertanyaan" required>
                    <div class="invalid-feedback">Teks pertanyaan tidak boleh kosong.</div>
                </div>
                
                <div class="form-group">
                    <label>Tipe Pertanyaan</label>
                    <select name="questions[][tipe_pertanyaan]" class="form-control question-type" required>
                        <option value="">Pilih Tipe Pertanyaan</option>
                        <option value="text">Teks</option>
                        <option value="multiple_choice">Pilihan Ganda</option>
                    </select>
                </div>
                
                <!-- Options for multiple choice (only show when "multiple_choice" is selected) -->
                <div class="form-group options-group" style="display: none;">
                    <label>Opsi Jawaban</label>
                    <input type="text" name="questions[][opsi_jawaban][]" class="form-control mb-2" placeholder="Masukkan opsi jawaban">
                    <button type="button" class="btn btn-secondary add-option">Tambah Opsi</button>
                </div>
                
                <button type="button" class="btn btn-danger remove-question">Hapus Pertanyaan</button>
                <hr>
            </div>

            <!-- Button to add questions -->
            <button type="button" id="add-question" class="btn btn-secondary mb-3">Tambah Pertanyaan</button>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Simpan Kuesioner</button>
    </form>
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
        newQuestion.querySelectorAll('input[name="questions[][opsi_jawaban][]"]').forEach(input => input.remove());

        // Event for changing question type
        newQuestion.querySelector('.question-type').addEventListener('change', function() {
            const optionsGroup = newQuestion.querySelector('.options-group');
            optionsGroup.style.display = this.value === 'multiple_choice' ? 'block' : 'none';
        });

        // Event for adding options
        newQuestion.querySelector('.add-option').addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'questions[][opsi_jawaban][]';
            input.classList.add('form-control', 'mb-2');
            input.placeholder = 'Masukkan opsi jawaban';
            newQuestion.querySelector('.options-group').insertBefore(input, this);
        });

        // Event for removing question
        newQuestion.querySelector('.remove-question').addEventListener('click', function() {
            newQuestion.remove();
        });

        questionSection.appendChild(newQuestion);
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

                const options = optionsGroups[index].querySelectorAll('input[name="questions[][opsi_jawaban][]"]');
                options.forEach(optionInput => {
                    if (optionInput.value.trim()) {
                        question.opsi_jawaban.push(optionInput.value);
                    }
                });

                formData.questions.push(question);
            }
        });

        // Kirim data ke API
        fetch('/api/kuesioner', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Jika diperlukan
            },
            body: JSON.stringify(formData),
        })
        .then(response => response.json())
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
        });
    });
});
</script>
@endsection