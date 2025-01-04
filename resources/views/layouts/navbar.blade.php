<div class="navbar bg-white transition-all duration-300 fixed top-0 left-0 right-0 z-50">
    <nav class="text-black px-4 py-3 h-16">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo dan Teks -->
            <div class="flex items-center">
                <img src="{{ asset('images/logo_pnb.png') }}" alt="Tracer Study" class="w-20 h-auto mr-2">
                <a href="#" class="text-lg font-bold">PNB X SITIKUS</a>
            </div>
            <!-- Menu -->
            <ul class="hidden md:flex space-x-6">
                <li><a href="{{ route('alumni.index') }}" class="font-medium hover:text-gray-500 hover:underline">Beranda</a></li>
                <li><a href="{{ route('alumni.statistik') }}" class="hover:text-gray-500 hover:underline">Statistik</a></li>
                <li><a href="{{ route('alumni.faq') }}" class="hover:text-gray-500 hover:underline">FAQ</a></li>
                <li><a href="{{ route('kuesioner.alumni.index') }}" class="hover:text-gray-500 hover:underline">Kuesioner</a></li>
                <li><a href="{{ route('alumni.login') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded hover:underline">Login</a></li>
            </ul>

            <!-- Mobile Menu Button -->
            <button id="menu-btn" class="md:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <ul class="space-y-2 mt-2">
                <li><a href="{{ route('alumni.index') }}" class="block text-blue-100 hover:bg-blue-700 px-4 py-2">Beranda</a></li>
                <li><a href="#Statistik" class="block text-blue-100 hover:bg-blue-700 px-4 py-2">Statistik</a></li>
                <li><a href="#" class="block text-blue-100 hover:bg-blue-700 px-4 py-2">FAQ</a></li>
                <li><a href="{{ route('kuesioner.alumni.index') }}" class="block text-blue-100 hover:bg-blue-700 px-4 py-2">Kuesioner</a></li>
                <li><a href="{{ route('alumni.login') }}" class="block text-blue-100 hover:bg-blue-700 px-4 py-2">Login</a></li>
            </ul>
        </div>
    </nav>
</div>

<script>
    // JavaScript untuk toggle menu mobile
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Mengubah background navbar saat scroll
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) { // Ganti 50 dengan nilai yang sesuai
            navbar.classList.remove('bg-white');
            navbar.classList.add('bg-transparent');
        } else {
            navbar.classList.add('bg-white');
            navbar.classList.remove('bg-transparent');
        }
    });
</script>

<style>
    .navbar {
        transition: background-color 0.3s ease;
    }
</style>