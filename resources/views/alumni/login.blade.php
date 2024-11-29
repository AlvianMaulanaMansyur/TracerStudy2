@extends('layouts.app')

@section('content')
<body>
    <div class="container mt-5">
        <h1 class="text-center">Login Alumni</h1>
        <form action="{{ route('alumni.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
@endsection('content')