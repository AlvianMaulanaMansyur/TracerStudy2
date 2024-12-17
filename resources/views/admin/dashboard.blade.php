</html>
@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <h2>Total Alumni: {{ $totalAlumni }}</h2>
    </div>
    <div class="container">
        <h1>Daftar Alumni</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Angkatan</th>
                    <th>Tahun Lulus</th>
                    <th>Gelombang Wisuda</th>
                    <th>Alamat</th>
                    <th>No Telp</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($alumni as $index => $key)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $key->nim }}</td>
                        <td>{{ $key->nama_alumni }}</td>
                        <td>{{ $key->email }}</td>
                        <td>{{ $key->angkatan }}</td>
                        <td>{{ $key->tahun_lulus }}</td>
                        <td>{{ $key->gelombang_wisuda }}</td>
                        <td>{{ $key->alamat }}</td>
                        <td>{{ $key->no_telepon }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center fixed-bottom mb-5">
            {{ $alumni->links('pagination.custom') }}
        </div>
    </div>
@endsection
