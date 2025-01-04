@extends('layouts.app')

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
        <div>{{ $filteredQuestions }}</div>
        {{-- <div>{{ $pertanyaan }}</div> --}}
        <div class="p-6">
            <!-- Form dimulai -->
            <form id="kuesionerForm" method="POST" action="{{ route('api.kuesioner.alumni.submit', $kuesioner->id) }}">
                @csrf
                <div>
                    @foreach ($filteredQuestions as $pertanyaan)
                    @php
                        $dataPertanyaan = json_decode($pertanyaan->data_pertanyaan);
                    @endphp
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            {{ $loop->iteration }}. {{ $dataPertanyaan->pertanyaan }}
                        </label>
                        @if ($dataPertanyaan->tipe_pertanyaan === 'text')
                        <div>{{ $pertanyaan->id }}</div>
                        <input type="text" name="jawaban[{{ $pertanyaan->id }}]" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-200" 
                            placeholder="Masukkan Jawaban">
                        @elseif ($dataPertanyaan->tipe_pertanyaan === 'checkbox')
                        @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                        <div class="flex items-center mb-2">
                            <div>{{ $pertanyaan->id }}</div>
                            <input type="checkbox" name="jawaban[{{ $pertanyaan->id }}][]" value="{{ $opsi }}" 
                                id="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}" 
                                class="mr-2">
                            <label for="opsi_{{ $pertanyaan->id }}_{{ $loop->index }}" class="text-gray-700">
                                {{ $opsi }}
                            </label>
                        </div>
                        @endforeach
                        @elseif ($dataPertanyaan->tipe_pertanyaan === 'radio')
                        @foreach ($dataPertanyaan->opsi_jawaban as $opsi)
                        <div class="flex items-center mb-2">
                            <div>{{ $pertanyaan->id }}</div>

                            <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $opsi }}" 
                                id="radio_{{ $pertanyaan->id }}_{{ $loop->index }}" 
                                class="mr-2">
                            <label for="radio_{{ $pertanyaan->id }}_{{ $loop->index }}" class="text-gray-700">
                                {{ $opsi }}
                            </label>
                        </div>
                        @endforeach
                        @else
                        <p class="text-red-500">Tipe pertanyaan tidak dikenali.</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </form>
            <!-- Form selesai -->
        </div>
        <div class="bg-gray-100 px-6 py-4 border-t flex justify-between items-center">
            <div class="flex items-center">
                <span class="text-sm text-gray-600 mr-4">Halaman {{ $currentPage }} dari {{ $totalPages }}</span>
            
                @if ($currentPage > 1)
                <button id="backButton" type="button" 
                class="text-sm bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">
                Kembali
            </button>
                @endif
            
                @if ($currentPage < $totalPages)
                <!-- Tombol Next -->
                <button id="nextButton" type="button" 
                    class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Next
                </button>
                @endif

                @if ($currentPage == $totalPages)
                <button id="submitButton" type="button" 
                    class="text-sm bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    Submit Jawaban
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        const nextPageUrl = "{{ route('kuesioner.alumni.show', ['id' => $kuesioner->id, 'page' => $currentPage + 1]) }}";
        const previousPageUrl = "{{ route('kuesioner.alumni.show', ['id' => $kuesioner->id, 'page' => $currentPage - 1]) }}";
        let answerData = JSON.parse(localStorage.getItem('kuesionerAnswers')) || {};

        // Memuat jawaban dari localStorage ke elemen form dan variabel answerData
        function loadAnswers() {
            console.log('Memuat jawaban...');
            $.each(answerData, function(name, value) {
                const input = $('[name="' + name + '"]');
                if (Array.isArray(value)) {
                    // Checkbox
                    input.each(function() {
                        if (value.includes($(this).val())) {
                            $(this).prop('checked', true);
                        }
                    });
                } else {
                    // Radio atau input lainnya
                    input.val(value);
                    if (input.is(':radio')) {
                        input.filter('[value="' + value + '"]').prop('checked', true);
                    }
                }
            });
        }

        // Menyimpan jawaban dari elemen form ke answerData dan localStorage
        function saveAnswers() {
            console.log('Menyimpan jawaban...');
            $('#kuesionerForm input').each(function() {
                const name = $(this).attr('name');
                if ($(this).is(':checkbox')) {
                    if (!answerData[name]) {
                        answerData[name] = [];
                    }
                    if ($(this).is(':checked') && !answerData[name].includes($(this).val())) {
                        answerData[name].push($(this).val());
                    }
                } else if ($(this).is(':radio')) {
                    if ($(this).is(':checked')) {
                        answerData[name] = $(this).val();
                    }
                } else {
                    answerData[name] = $(this).val();
                }
            });

            localStorage.setItem('kuesionerAnswers', JSON.stringify(answerData));
            console.log('Data tersimpan:', answerData);
        }

        // Submit form menggunakan AJAX
        function submitForm(isFinalSubmit) {
            const form = $('#kuesionerForm');
            const actionUrl = form.attr('action') || '/default-endpoint';
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    alert('Jawaban berhasil disimpan!');
                    if (!isFinalSubmit) {
                        window.location.href = nextPageUrl;
                    } else {
                        console.log('Semua jawaban telah disubmit.');
                        localStorage.removeItem('kuesionerAnswers');
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        }

        // Event handler tombol navigasi
        $('#nextButton').click(function() {
            saveAnswers();
            submitForm(false);
        });

        $('#submitButton').click(function() {
            saveAnswers();
            submitForm(true);
        });

        $('#backButton').click(function() {
            saveAnswers();
            window.location.href = previousPageUrl;
        });

        // Muat jawaban saat halaman dimuat
        loadAnswers();
    });
</script>


@endsection