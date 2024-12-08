@extends('auth.app')

@section('content')

    <body class="m-0 p-0 overflow-hidden background-image">
        <div class="relative w-screen h-screen">
            <div class="relative z-10 bg-white bg-opacity-80 p-6 rounded-lg max-w-md mx-auto mt-20 shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Admin Login</h2>
                @if ($errors->any())
                    <div style="color: red;" class="mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                        <input type="text" id="nip" name="nip" required
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                    </div>
                    <button type="submit"
                        class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Login</button>
                </form>
            </div>
        </div>
    </body>
@endsection
