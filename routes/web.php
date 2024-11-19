<?php

use App\Http\Middleware\AlumniMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Alumni\AuthAlumni;
use App\Http\Controllers\AlumniController;
use App\Models\Alumni;

Route::get('/', [AlumniController::class, 'index'])->name('alumni.index');
// Rute untuk halaman kuesioner

// Rute untuk halaman login alumni
Route::get('/login', [AuthAlumni::class, 'showLoginForm'])->name('alumni.login');
Route::middleware([AlumniMiddleware::class])->group(function () {
    Route::get('/kuesioner', [KuesionerController::class, 'index'])->name('kuesioner.index');
});

// Rute untuk admin
Route::prefix('admin')->group(function () {
    // Admin authentication routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Protected admin routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('kuesioner', KuesionerController::class);
        Route::get('/kuesioner/{id}', [KuesionerController::class, 'show'])->name('kuesioner.show');

        Route::resource('kuesioner.pertanyaan', PertanyaanController::class)->shallow();

        Route::post('/kuesioner/{id}/submit', [KuesionerController::class, 'submit'])->name('kuesioner.submit');

        Route::get('/kuesioner/{id}/edit', [KuesionerController::class, 'edit'])->name('kuesioner.edit');
        Route::put('/kuesioner/{id}', [KuesionerController::class, 'update'])->name('kuesioner.update');
    });
});

// Rute API untuk Kuesioner
Route::prefix('api')->group(function () {
    Route::post('/kuesioner', [KuesionerController::class, 'store']);
    Route::put('/kuesioner/{id}', [KuesionerController::class, 'update']);
});