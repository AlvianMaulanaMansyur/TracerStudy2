@extends('layouts.auth')

@section('content')

    <body class="m-0 p-0 overflow-hidden">
        <div class="relative w-screen h-screen"
            style="background-image: url('{{ asset('images/foto_login.jpg') }}'); background-size: cover; background-position: center;">
            <!-- Overlay untuk efek blur -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-black/55 to-black/100"></div>

            <!-- Konten Login -->
            <div class="relative z-10 flex items-center justify-end h-screen w-screen pr-40">
                <div
                    class="relative bg-white bg-opacity-0 border-2 p-6 rounded-2xl max-w-md mx-10 px-12 shadow-lg flex flex-col justify-center items-center">
                    <img src="{{ asset('images/PNB.png') }}" alt="" class="w-16 h-auto mt-6">

                    <h3 class="text-xl font-bold text-white ">SITIKA</h3>
                    <h3 class="text-xl font-bold text-white ">POLITEKNIK NEGERI BALI</h3>

                    @if ($errors->any())
                        <div style="color: red;" class="mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('alumni.login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            {{-- <label for="nip" class="block text-sm font-medium text-white">NIP</label> --}}
                            <input type="text" id="nim" name="nim" required
                                class=" w-72 border border-gray-300 rounded-md" placeholder="Masukan NIM">
                        </div>
                        <!-- Form Group -->
                        <div class="max-w-sm mb-8">
                            <div class="relative">
                                <input id="hs-toggle-password" type="password" id="password" name="password" required
                                    class="py-3 ps-4 pe-10 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    placeholder="Masukan Password">
                                <button type="button" data-hs-toggle-password='{ "target": "#hs-toggle-password"}'
                                    class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600">
                                    <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                        <path class="hs-password-active:hidden"
                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                                        </path>
                                        <path class="hs-password-active:hidden"
                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61">
                                        </path>
                                        <line class="hs-password-active:hidden" x1="2" x2="22" y1="2"
                                            y2="22"></line>
                                        <path class="hidden hs-password-active:block"
                                            d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3">
                                        </circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- End Form Group -->
                        <button type="submit"
                            class="w-full py-2 px-4 mb-10 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection