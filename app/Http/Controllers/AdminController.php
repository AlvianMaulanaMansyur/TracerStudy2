<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard'); // This view should be created for the dashboard page
    }

    public function tampilAlumni()
    {
        $totalAlumni = Alumni::count();
        $alumni = Alumni::paginate(10);
        return view('admin.dashboard', compact('alumni', 'totalAlumni'));
    }
}
