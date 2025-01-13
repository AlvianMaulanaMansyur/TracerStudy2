<footer class="bg-gray-700 text-white py-6 mt-2 shadow">
    <div class="container mx-auto">
        <div class="flex items-center mb-6"> <!-- Flexbox untuk gambar dan judul -->
            <img src="{{ asset('images/logo_pnb.png') }}" alt="Logo" class="w-24 h-24 object-cover mr-4"> <!-- Gambar -->
            <div>
                <h2 class="text-2xl font-semibold mb-1">Tracer Study Jurusan Teknologi Informasi</h2>
                <h2 class="text-lg font-semibold mb-5">Politeknik Negeri Bali</h2>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4"> <!-- Flexbox untuk konten di bawah -->
            <!-- Konten di bawah gambar dan judul -->
            <ul class="hidden md:flex space-x-6 mt-24"> <!-- Menggunakan space-x-6 untuk jarak antar item -->
                <li><a href="{{ route('alumni.index') }}" class="font-medium hover:text-gray-500 hover:underline">Beranda</a></li>
                <li><a href="{{ route('alumni.statistik') }}" class="hover:text-gray-500 hover:underline">Statistik</a></li>
                <li><a href="{{ route('alumni.faq') }}" class="hover:text-gray-500 hover:underline">FAQ</a></li>
                <li><a href="{{ route('kuesioner.alumni.index') }}" class="hover:text-gray-500 hover:underline">Kuesioner</a></li>
                <li><a href="{{ route('alumni.login') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded hover:underline">Login</a></li>
            </ul>

            <!-- Konten di sebelah kanan -->
            <ul class="flex space-x-6 mt-24"> <!-- Menggunakan space-x-6 untuk jarak antar item -->
                <li><a href="https://www.instagram.com/teknologiinformasi.pnb?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="hover:text-gray-500 hover:underline" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                <li><a href="#" class="hover:text-gray-500 hover:underline">Email</a></li>
            </ul>
        </div>

        <p class="mb-2">&copy; {{ date('Y') }} Politeknik Negeri Bali</p>
    </div>
</footer>