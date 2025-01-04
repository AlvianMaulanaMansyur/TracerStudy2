<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite('resources/css/app.css')
  @vite(['resources/js/app.js'])

  <title>@yield('title')</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
@include('layouts.navbar')
  <main>
    @yield('content')
  </main>

  <footer>
    @include('layouts.footer')
  </footer>

  @yield('scripts')
</body>
</html>