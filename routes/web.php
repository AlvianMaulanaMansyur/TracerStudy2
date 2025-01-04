<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\AuthAdmin;
use App\Http\Controllers\AuthAlumni;
use App\Http\Controllers\AlumniController;

Route::get('/', [AlumniController::class, 'index'])->name('alumni.index');
Route::get('/faq', [AlumniController::class, 'faq'])->name('alumni.faq');
Route::get('/statistik', [AlumniController::class, 'statistik'])->name('alumni.statistik');


// Rute untuk alumni
Route::post('/login', [AuthAlumni::class, 'login']);
Route::get('/login', [AuthAlumni::class, 'showLoginForm'])->name('alumni.login');
// Protected Alumni Routes
Route::middleware([AlumniMiddleware::class])->group(function () {
    Route::get('/kuesioner', [KuesionerController::class, 'KuesionerForAlumni'])->name('kuesioner.alumni.index');
    Route::get('/kuesioner/{id}', [KuesionerController::class, 'ShowKuesionerForAlumni'])->name('kuesioner.alumni.show');
    Route::post('/kuesioner/{id}/submit', [KuesionerController::class, 'submit'])->name('kuesioner.alumni.submit');
});

// Rute untuk admin
Route::prefix('admin')->group(function () {
    // Admin authentication routes
    Route::get('/login', [AuthAdmin::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthAdmin::class, 'login']);
    Route::post('/logout', [AuthAdmin::class, 'logout'])->name('admin.logout');

    // Protected admin routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('kuesioner', KuesionerController::class);
        Route::get('/kuesioner/{id}', [KuesionerController::class, 'show'])->name('kuesioner.admin.show');

        Route::resource('kuesioner.pertanyaan', PertanyaanController::class)->shallow();

        Route::post('/kuesioner/{id}/submit', [KuesionerController::class, 'submit'])->name('kuesioner.admin.submit');

        Route::get('/kuesioner/{id}/edit', [KuesionerController::class, 'edit'])->name('kuesioner.admin.edit');
        Route::put('/kuesioner/{id}', [KuesionerController::class, 'update'])->name('kuesioner.admin.update');
    });
});

Route::middleware([AdminMiddleware::class])->group(function () {
    // Rute Admin API untuk Kuesioner
    Route::prefix('api')->group(function () {
        Route::post('/kuesioner', [KuesionerController::class, 'store']);
        Route::put('/kuesioner/{id}', [KuesionerController::class, 'update']);
    });
});

Route::middleware([AlumniMiddleware::class])->group(function () {
    // Rute Alumni API untuk Kuesioner
    Route::prefix('api')->group(function () {
        Route::post('/kuesioner/{id}/submit', [KuesionerController::class, 'submit'])->name('api.kuesioner.alumni.submit');
    });
});
=======

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
>>>>>>> e0ac125d2f2d19f460f11a7f9502c54c5a07d17a
