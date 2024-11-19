@extends('layouts.app')

@section('content')

<body>
    <div class="container mt-5">
        <h1 class="text-center">Selamat Datang di Halaman Alumni</h1>
        <p class="text-center">Ini adalah halaman home untuk alumni.</p>

        <h2>Informasi Terbaru</h2>
        <ul class="list-group">
            <li class="list-group-item">Acara reuni akan diadakan pada tanggal 20 Desember 2023.</li>
            <li class="list-group-item">Silakan perbarui informasi kontak Anda di profil alumni.</li>
            <li class="list-group-item">Jangan lewatkan kesempatan untuk berkontribusi pada program beasiswa.</li>
        </ul>

        <h2 class="mt-4">Daftar Alumni</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tahun Lulus</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>2020</td>
                    <td>johndoe@example.com</td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>2019</td>
                    <td>janesmith@example.com</td>
                </tr>
            </tbody>
        </table>

        <!-- Tombol untuk mengarahkan ke halaman kuesioner -->
        <div class="text-center mt-4">
            @if(auth()->check())
                <a href="{{ route('kuesioner.index') }}" class="btn btn-primary">Lihat Kuesioner</a>
            @else
                <a href="{{ route('alumni.login') }}" class="btn btn-warning">Login untuk Mengakses Kuesioner</a>
            @endif
        </div>
    </div>
</body>
@endsection('content')