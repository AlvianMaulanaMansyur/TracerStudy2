@extends('layouts.app')

@section('content')

<div class="relative h-screen">
    <!-- Konten Gambar -->
    <img id="beranda" src="{{ asset('images/foto_login.jpg') }}" alt="Tracer Study" class="w-full h-full object-cover">
    <div class="absolute inset-0 flex justify-center items-center text-white bg-black/50 p-4 z-10 animate__animated animate__fadeIn">
        <!-- Wrapper untuk Konten -->
        <div class="flex justify-between items-center w-4/5">
            <!-- Teks di Kiri -->
            <div class="flex flex-col justify-center items-start w-1/2 p-4">
                <p class="text-2xl">Selamat Datang!</p>
                <h1 class="text-3xl font-bold">Di Halaman FAQ<br>Jurusan Teknologi Informasi</h1>
                <p class="mt-4">Anda dapat mengetahui informasi mengenai Tracer Study <br>
                    Jurusan Teknologi Informasi disini.
                    Untuk pertanyaan lainnya <br>
                    dapat dikirim melalui Email dibawah.</p>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=admin@example.com&su=Pertanyaan%20Tracer%20Study&body=Halo%20Admin,%20saya%20mempunyai%20pertanyaan%20tentang%20Tracer%20Study."
                    class="bg-yellow-500 hover:bg-yellow-600 text-black py-2 px-4 rounded shadow mt-4 transition duration-200 hover:underline">
                    Kirim Pertanyaan
                </a>
            </div>

            <!-- Konten FAQ di Kanan -->
            <div class="text-white p-4 rounded-lg shadow-lg w-1/3 max-h-[80%] overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">Frequently Asked Questions (FAQ)</h2>

                <div class="p-3 rounded-lg shadow-md mb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Apa itu Tracer Study?</h3>
                        <button onclick="toggleAnswer('answer1')" class="text-white-500 hover:text-white-700 transition">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <p id="answer1" class="mt-2 hidden text-sm">Tracer Study adalah sebuah penelitian yang dilakukan untuk melacak alumni dan mengetahui perkembangan karir mereka setelah lulus dari jurusan.</p>
                </div>

                <div class="p-3 rounded-lg shadow-md mb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Bagaimana cara mengisi kuesioner Tracer Study?</h3>
                        <button onclick="toggleAnswer('answer2')" class="text-white-500 hover:text-white-700 transition">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <p id="answer2" class="mt-2 hidden text-sm">Anda dapat mengisi kuesioner Tracer Study dengan mengklik tombol "Isi Kuesioner" di atas dan mengikuti instruksi yang diberikan.</p>
                </div>

                <div class="p-3 rounded-lg shadow-md mb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Apakah data yang saya berikan akan dirahasiakan?</h3>
                        <button onclick="toggleAnswer('answer3')" class="text-white-500 hover:text-white-700 transition">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <p id="answer3" class="mt-2 hidden text-sm">Ya, semua data yang Anda berikan akan dijaga kerahasiaannya dan hanya akan digunakan untuk keperluan penelitian.</p>
                </div>

                <div class="p-3 rounded-lg shadow-md mb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Siapa yang dapat mengikuti Tracer Study?</h3>
                        <button onclick="toggleAnswer('answer4')" class="text-white-500 hover:text-white-700 transition">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <p id="answer4" class="mt-2 hidden text-sm">Semua alumni Jurusan Teknologi Informasi diharapkan untuk berpartisipasi dalam Tracer Study ini.</p>
                </div>

                <div class="p-3 rounded-lg shadow-md mb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Bagaimana jika saya memiliki pertanyaan lain?</h3>
                        <button onclick="toggleAnswer('answer5')" class="text-white-500 hover:text-white-700 transition">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <p id="answer5" class="mt-2 hidden text-sm">Jika Anda memiliki pertanyaan lain, silakan kirim email ke alamat yang tertera di bawah.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAnswer(answerId) {
        const answerElement = document.getElementById(answerId);
        const icon = answerElement.previousElementSibling.querySelector('i');

        // Menutup semua jawaban yang terbuka
        const allAnswers = document.querySelectorAll('.p-3 p');
        allAnswers.forEach((answer) => {
            if (!answer.classList.contains('hidden') && answer.id !== answerId) {
                answer.classList.add('hidden');
                // Mengubah ikon kembali ke bawah
                const answerButton = answer.previousElementSibling.querySelector('i');
                answerButton.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        });

        // Toggle jawaban yang diklik
        answerElement.classList.toggle('hidden');
        if (answerElement.classList.contains('hidden')) {
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        } else {
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        }
    }
</script>

@endsection
