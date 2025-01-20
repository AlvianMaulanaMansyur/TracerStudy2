@extends('layouts.admin')

@section('content')
    <div class=" mb-2 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="justify-center align-items-center ">
                    <h1>Welcome Back, Admin Politeknik Negeri Bali!</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6  mb-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center ">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 ">
                                jumlah alumni</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlumni }}</div>
                        </div>
                        <div class="col-auto text-3xl me-4">
                            <i class="fa-regular fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" col-md-6 mb-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Program Studi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                3
                            </div>
                        </div>
                        <div class="col-auto me-4">
                            <i class="fa-solid fa-book-open fa-xl" ></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 flex justify-between items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Alumni</h6>
            <div class="flex space-x-2">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.alumni.search') }}" class="flex items-center">
                    <input type="text" name="search" placeholder="Cari Alumni" class="form-control rounded" />
                    <button type="submit" class="btn btn-primary ml-2"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></button>
                </form>
                <!-- Filter Button -->
                <button type="button" class="btn btn-secondary ml-2" data-toggle="modal" data-target="#filterModal">
                    <i class="fa-solid fa-sliders" style="color: #ffffff;"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="">
                        <tr class="justify-center">
                            <th width="200">NIM</th>
                            <th width="50">Foto Profile</th>
                            <th>Nama</th>
                            <th>Angkatan</th>
                            <th width="50">Prodi</th>
                            <th width="50">detail</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($alumni as $index => $key)
                            <tr>
                                <td>{{ $key->nim }}</td>
                                <td><img src="{{ asset($key->foto_profil) }}" alt="foto profil" width="50"></td>
                                <td>{{ $key->nama_alumni }}</td>
                                <td>{{ $key->angkatan }}</td>
                                <td>{{ $key->prodi->nama_prodi }}</td>


                                <td class="">
                                    <button data-modal-target="default-modal" data-modal-toggle="default-modal"
                                        class="block text-white bg-yellow-500 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center dark:bg-yellow-500 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                        type="button"
                                        onclick="showModal(
                                            '{{ $key->nim }}',
                                            '{{ $key->nama_alumni }}',
                                            '{{ $key->angkatan }}',
                                            '{{ asset($key->foto_profil) }}',
                                            '{{ $key->email }}',
                                            '{{ $key->jenjang }}',
                                            '{{ $key->nik }}',
                                            '{{ $key->prodi->nama_prodi }}',
                                            '{{ $key->no_telepon }}',

                                        )">
                                        <i class="fa-regular fa-eye" style="color: #ffffff;"></i>
                                    </button>

                                </td>
                            </tr>

                            {{-- Modal --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $alumni->links('pagination.custom') }}
            </div>
        </div>
    </div>

    @include('layouts.partials.modalfilter')

    @include('layouts.partials.modal')
@endsection
