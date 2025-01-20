@extends('layouts.alumniDashboard')

@section('content')

<div class="container mx-full my-10 px-4">
    <div class="mx-auto bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class=" mb-6">
            <h1 class="text-xl font-bold text-gray-800">DETAIL ALUMNI</h1>
        </div>

        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

        <!-- Foto Profil -->
        <div class="flex justify-center mb-6"> <!-- Menambahkan margin bawah di sini -->
            <div class="w-36 h-36 rounded overflow-hidden border-2 border-gray-300">
                <img src="{{ asset($profil->foto_profil) }}" alt="Foto Profil" class="object-cover w-full h-full">
            </div>
        </div>

        <!-- Profile Section -->
        <div class="flex flex-col md:flex-row items-start">
            <!-- Informasi Profil -->
            <div class="flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([
                        'NIM' => $profil->nim,
                        'Nama' => $profil->nama_alumni,
                        'Email' => $profil->email,
                        'Jenis Kelamin' => $profil->jenis_kelamin,
                        'Angkatan' => $profil->angkatan,
                        'Tahun Lulus' => $profil->tahun_lulus,
                        // 'Alamat' => $profil->alamat,
                        'No. Telepon' => $profil->no_telepon,
                        // 'Status Verifikasi' => ucfirst($profil->status_verifikasi),
                    ] as $label => $value)
                        <div>
                            <p class="text-gray-600"><strong>{{ $label }}:</strong></p>
                            <p class="text-gray-800 font-medium">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Edit Button -->
        <div class="text-center mt-6">
            <a href="{{ route('alumni.profil.edit') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md transition duration-200">
                EDIT PROFIL
            </a>
        </div>
    </div>
</div>

@endsection
