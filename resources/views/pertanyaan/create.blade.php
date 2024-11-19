<form action="{{ route('pertanyaan.store', $kuesioner->id) }}" method="POST">
    @csrf
    <label for="teks_pertanyaan">Question Text:</label>
    <input type="text" name="teks_pertanyaan" required>

    <label for="tipe_pertanyaan">Question Type:</label>
    <select name="tipe_pertanyaan" required>
        <option value="text">Text</option>
        <option value="multiple_choice">Multiple Choice</option>
    </select>

    <label for="opsi_jawaban">Answer Options (if multiple choice):</label>
    <textarea name="opsi_jawaban"></textarea>

    <button type="submit">Add Question</button>
</form>
