<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite('resources/css/app.css')
  <title>@yield('title')</title> <!-- Tempat untuk judul halaman -->
</head>
<body>

  <main>
    @yield('content') <!-- Tempat untuk konten dinamis -->
  </main>

  <footer>
    <p>© 2023 My Application</p>
  </footer>
</body>
</html>