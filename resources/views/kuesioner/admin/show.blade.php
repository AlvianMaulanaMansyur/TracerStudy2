@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Detail Kuesioner</h1>

    @if(session('success'))
    <div class="bg-green-100 text-green-700 border border-green-400 rounded px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="bg-gray-100 px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">{{ $kuesioner->judul_kuesioner }}</h2>
        </div>
        <div class="p-6">
            @csrf
            <div>
            <div>{{ $previousPage }}</div>

                @foreach ($filteredQuestions as $pertanyaan)
                @php
                    $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan, true);
                @endphp
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        {{ $loop->iteration }}. {{ $dataPertanyaan['pertanyaan'] }}
                    </label>
                    @if ($dataPertanyaan['tipe_pertanyaan'] === 'checkbox')
                    @foreach ($dataPertanyaan['opsi_jawaban'] as $opsi)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="jawaban[{{ $pertanyaan->id }}][]" value="{{ $opsi['nilai'] }}" 
                                id="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}" 
                                class="mr-2">
                            <label for="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}" class="text-gray-700">
                                {{ $opsi['nilai'] }}
                            </label>
                        </div>
                    @endforeach
                @elseif ($dataPertanyaan['tipe_pertanyaan'] === 'radio')
                    @foreach ($dataPertanyaan['opsi_jawaban'] as $opsi)
                        <div class="flex items-center mb-2">
                            <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $opsi['nilai'] }}" 
                                id="radio_{{ $pertanyaan->id }}_{{ $loop->index }}" 
                                class="mr-2">
                            <label for="radio_{{ $pertanyaan->id }}_{{ $loop->index }}" class="text-gray-700">
                                {{ $opsi['nilai'] }}
                            </label>
                        </div>
                    @endforeach
                @elseif ($dataPertanyaan['tipe_pertanyaan'] === 'text')
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="text_{{ $pertanyaan->id }}">
                            {{ $dataPertanyaan['pertanyaan'] }}
                        </label>
                        <input type="text" name="jawaban[{{ $pertanyaan->id }}]" 
                            id="text_{{ $pertanyaan->id }}" 
                            class="border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300" 
                            placeholder="Masukkan jawaban Anda">
                    </div>
                @else
                    <p class="text-red-500">Tipe pertanyaan tidak dikenali.</p>
                @endif
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-gray-100 px-6 py-4 border-t flex justify-between items-center">
            <div>
                <a href="{{ route('kuesioner.index') }}" 
                class="text-sm bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-300">
                Kembali ke Daftar Kuesioner
            </a>
            <a href="{{ route('kuesioner.edit', $kuesioner->id) }}" 
                class="text-sm bg-yellow-500 text-white px-4 py-2 rounded-lg mr-2 hover:bg-yellow-600">
                Edit Kuesioner
            </a>
            <form action="{{ route('kuesioner.destroy', $kuesioner->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="text-sm bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus kuesioner ini?')">
                    Hapus Kuesioner
                </button>
            </form>
        </div>
        <div class="flex items-center">
            {{-- <span class="text-sm text-gray-600 mr-4">Halaman {{ $currentPage }} dari {{ $totalPages }}</span> --}}

            @if ($previousPage)
            <a href="{{ route('kuesioner.admin.show', ['id' => $kuesioner->id, 'page' => $previousPage]) }}" 
                class="text-sm bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">
                Kembali
            </a>
            @endif
        
            @if ($currentPage < $totalPages)
            <!-- Tombol Next -->
            <button id="nextButton" class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Next
            </button>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil logika dari server
        const logics = @json($filteredQuestions->map(function($pertanyaan) {
            return json_decode($pertanyaan->data_pertanyaan, true)['logika'] ?? [];
        }));

        // Flatten logika untuk memudahkan pencarian
        const flattenedLogics = logics
            .filter(item => Array.isArray(item) && item.length > 0) // Hanya array yang tidak kosong
            .flatMap(item => item);

        // Ambil semua input radio dan checkbox
        const options = document.querySelectorAll('input[name^="jawaban"]');

        options.forEach(option => {
            option.addEventListener('change', function() {
                const selectedValue = this.value;

                // Hapus pertanyaan tambahan yang sudah ada sebelumnya
                const existingQuestions = document.querySelectorAll('.additional-question');
                existingQuestions.forEach(q => q.remove());

                // Cari logika yang cocok dengan pilihan pengguna
                const matchedLogic = flattenedLogics.find(log => log.opsi === selectedValue);

                if (matchedLogic) {
                    // Tampilkan pertanyaan tambahan
                    matchedLogic.pertanyaan.forEach(q => {
                        const questionContainer = document.createElement('div');
                        questionContainer.classList.add('additional-question', 'mb-4');

                        const questionLabel = document.createElement('label');
                        questionLabel.textContent = q.text;

                        let inputElement;
                        if (q.type === 'radio') {
                            inputElement = document.createElement('input');
                            inputElement.type = 'radio';
                            inputElement.name = 'additional_question'; // Nama untuk pertanyaan tambahan
                        } else if (q.type === 'checkbox') {
                            inputElement = document.createElement('input');
                            inputElement.type = 'checkbox';
                        } else {
                            inputElement = document.createElement('input');
                            inputElement.type = 'text'; // Default ke text
                        }

                        questionContainer.appendChild(questionLabel);
                        questionContainer.appendChild(inputElement);
                        document.querySelector('.p-6').appendChild(questionContainer); // Menambahkan ke kontainer pertanyaan
                    });

                    // Tampilkan opsi baru
                    matchedLogic.opsiBaru.forEach(opsiBaru => {
                        const newOptionContainer = document.createElement('div');
                        newOptionContainer.classList.add('new-option', 'mb-4');

                        const newOptionLabel = document.createElement('label');
                        newOptionLabel.textContent = opsiBaru;

                        const newOptionInput = document.createElement('input');
                        newOptionInput.type = 'text'; // Atau bisa disesuaikan dengan tipe yang diinginkan

                        newOptionContainer.appendChild(newOptionLabel);
                        newOptionContainer.appendChild(newOptionInput);
                        document.querySelector('.p-6').appendChild(newOptionContainer); // Menambahkan ke kontainer pertanyaan
                    });
                }
            });
        });

        // Tombol Next
        const nextButton = document.getElementById('nextButton');
        
        if (nextButton) {
            nextButton.addEventListener('click', function() {
                const selectedAnswers = {};
                const options = document.querySelectorAll('input[name^="jawaban"]');

                options.forEach(option => {
                    if (option.type === 'checkbox') {
                        if (option.checked) {
                            if (!selectedAnswers[option.name]) {
                                selectedAnswers[option.name] = [];
                            }
                            selectedAnswers[option.name].push(option.value);
                        }
                    } else if (option.type === 'radio' && option.checked) {
                        selectedAnswers[option.name] = option.value;
                    }
                });

                // Simpan pilihan di session atau kirim ke server
                fetch('{{ route('kuesioner.saveChoice') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ answers: selectedAnswers })
                }).then(response => {
                    if (response.ok) {
                        // Arahkan ke halaman berikutnya
                        const nextPage = {{ $currentPage + 1 }};
                        const kuesionerId = {{ $kuesioner->id }};
                        window.location.href = `{{ url('admin/kuesioner') }}/${kuesionerId}?page=${nextPage}`;
                    } else {
                        alert('Gagal menyimpan pilihan. Silakan coba lagi.');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan.');
                });
            });
        }
    });
</script>
@endsection