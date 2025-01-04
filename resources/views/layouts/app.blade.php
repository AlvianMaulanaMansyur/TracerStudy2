<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite('resources/css/app.css')
  {{-- @vite(['resources/js/app.js']) --}}

  <title>@yield('title')</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    .sticky {
    position: sticky;
    top: 0; /* Jarak dari atas saat di-scroll */
    background-color: white; /* Warna latar belakang untuk menjaga visibilitas */
    z-index: 10; /* Pastikan elemen ini berada di atas elemen lainnya */
    }

    .modal {
    display: block; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 5000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.hidden {
    display: none; /* Class to hide the modal */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
}

.close-button {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-button:hover,
.close-button:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

  </style>

<style>

.highlight {
    /* border: 2px dashed #4AAE9B; Change the border color to indicate a valid drop zone */
    /* background-color: rgba(74, 174,  155, 0.1);  */
}
.question-container {
    padding: 10px; /* Padding awal */
    border: 2px solid transparent; /* Border transparan untuk menghindari pergeseran */
    transition: border-color 0.13s; /* Menambahkan transisi untuk efek halus */
}

.question-container:hover {
    border-color: #3b82f6; /* Mengubah warna border saat hover */
}
  .question-container {
      position: relative;
  }

  .question-container:hover .buttons-container {
      display: block; /* Tampilkan tombol hapus saat hover */
  }

  .editing {
    border-color: #3b82f6; /* Change border color when editing */
}

.editing .buttons-container {
    display: block; /* Show buttons container when editing */
}
</style>
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