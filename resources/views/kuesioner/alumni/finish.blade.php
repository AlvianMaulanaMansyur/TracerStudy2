@extends('layouts.alumniDashboard')

@section('content')
    <div class="container mx-auto mt-10 px-4 text-center">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Terima Kasih!</h1>
            <p class="text-gray-600 mb-6">
                Kami menghargai waktu dan kontribusi Anda dalam mengisi kuesioner ini. Data yang Anda berikan sangat berarti untuk membantu kami meningkatkan layanan.
            </p>
            <a href="{{ route('kuesioner.alumni.index') }}" 
               class="bg-blue-500 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Kembali ke Kuesioner
            </a>
        </div>
    </div>
@endsection
