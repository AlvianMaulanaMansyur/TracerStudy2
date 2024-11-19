<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home'); // pastikan ada file home.blade.php di resources/views
    }

    public function about()
    {
        return view('about'); // pastikan ada file about.blade.php di resources/views
    }

    public function contact()
    {
        return view('contact'); // pastikan ada file contact.blade.php di resources/views
    }
}
