@extends('layouts.admin')

@section('content')
    <div class=" mb-2 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="justify-center align-items-center ">
                    <h1>Welcome Back</h1>
                    <h2>Admin</h2>
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
                                Data Responden</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                isinya
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Alumni</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Foto Profile</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            {{-- <th>Email</th> --}}
                            <th>Angkatan</th>
                            {{-- <th>Tahun Lulus</th> --}}
                            {{-- <th>Gelombang Wisuda</th> --}}
                            {{-- <th>Alamat</th> --}}
                            {{-- <th>No Telp</th> --}}
                            <th>action</th>

                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($alumni as $index => $key)
                            <tr>
                                <td><img src="{{ asset($key->foto_profil) }}" alt="foto profil" width="50"></td>
                                <td>{{ $key->nim }}</td>
                                <td>{{ $key->nama_alumni }}</td>
                                {{-- <td>{{ $key->email }}</td> --}}
                                <td>{{ $key->angkatan }}</td>
                                {{-- <td>{{ $key->tahun_lulus }}</td> --}}
                                {{-- <td>{{ $key->gelombang_wisuda }}</td> --}}
                                {{-- <td>{{ $key->alamat }}</td> --}}
                                {{-- <td>{{ $key->no_telepon }}</td> --}}
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#editModal"
                                        onclick="showModal('{{ $key->nim }}', '{{ $key->nama_alumni }}', '{{ $key->angkatan }}')">test</button>
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
    @include('layouts.partials.modal')
@endsection
