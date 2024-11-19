@extends('layouts')

@section('content')
<body>
    <h2>Admin Login</h2>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div>
            <label for="nip">NIP</label>
            <input type="text" id="nip" name="nip" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
@endsection('content')
