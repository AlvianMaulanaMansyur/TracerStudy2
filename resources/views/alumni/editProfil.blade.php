@extends('layouts.alumniDashboard')

@section('content')

<div class="container">

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('alumni.profil.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-6 pt-6 pb-6 mb-6 mx-auto"> <!-- Mengubah padding atas dan bawah menjadi 6 -->
        <div class="text-2xl mb-3">
            <h2>Edit Profile</h2>
        </div>
        @csrf

        <div class="mb-4">
            <label for="nama_alumni" class="block text-gray-700 text-sm font-bold mb-1">Nama:</label>
            <input type="text" id="nama_alumni" name="nama_alumni" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('nama_alumni', $currentUser  ->nama_alumni) }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-1">Email:</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('email', $currentUser  ->email) }}" required>
        </div>

        <div class="mb-4">
            <label for="no_telepon" class="block text-gray-700 text-sm font-bold mb-1">No. Telepon:</label>
            <input type="text" id="no_telepon" name="no_telepon" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('no_telepon', $currentUser  ->no_telepon) }}">
        </div>

        <div class="mb-4">
            <label for="foto_profil" class="block text-gray-700 text-sm font-bold mb-1">Foto Profil:</label>
            @if ($currentUser  ->foto_profil)
                <img src="{{ asset($currentUser  ->foto_profil) }}" alt="Foto Profil" class="mb-2 rounded border" style="width: 80px; height: 80px;">
            @endif
            <input type="file" id="foto_profil" name="foto_profil" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan Perubahan</button>
    </form>
</div>

@endsection
