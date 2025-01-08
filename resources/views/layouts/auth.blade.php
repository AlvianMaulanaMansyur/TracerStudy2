<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    @vite(['resources/js/app.js'])
    @vite('resources/js/app.js')    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>@yield('title')</title>
</head>

<body>
    <main>
        @yield('content')
    </main>

    <footer>
        <p>© 2023 My Application</p>
    </footer>

    @yield('scripts')
    
</body>

</html>
