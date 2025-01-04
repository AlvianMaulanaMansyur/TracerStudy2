<?php

use App\Http\Middleware\AlumniMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\AuthAdmin;
use App\Http\Controllers\AuthAlumni;
use App\Http\Controllers\AlumniController;

Route::get('/', [AlumniController::class, 'index'])->name('alumni.index');

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
    Route::post('/logout', [AuthAdmin::class, 'logou    t'])->name('admin.logout');

    // Admin dashboard routes
    Route::get('/alumni', [AdminController::class, 'index'])->name('admin.alumni.index');
    Route::get('/alumni/search', [AdminController::class, 'search'])->name('admin.alumni.search');
    Route::get('/alumni/filter', [AdminController::class, 'filter'])->name('admin.alumni.filter');

    // Route::get('admin', function () {
    //     return view('admin.dashboard');
    // })->name('admin.dashboard');

    // Protected admin routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'tampilAlumni'])->name('admin.dashboard');
        Route::resource('kuesioner', KuesionerController::class);
        Route::get('/kuesioner', [KuesionerController::class, 'index'])->name('kuesioner.index');
        Route::get('/kuesioner/{id}', [KuesionerController::class, 'show'])->name('kuesioner.admin.show');

        Route::resource('kuesioner.pertanyaan', PertanyaanController::class)->shallow();
       

        Route::post('/kuesioner/{id}/submit', [KuesionerController::class, 'submit'])->name('kuesioner.admin.submit');

        Route::get('/kuesioner/{id}/edit', [KuesionerController::class, 'edit'])->name('kuesioner.admin.edit');
        Route::put('/kuesioner/{id}', [KuesionerController::class, 'update'])->name('kuesioner.admin.update');

        Route::post('kuesioner/saveChoice', [KuesionerController::class, 'saveChoice'])->name('kuesioner.saveChoice');

        Route::get('/kuesioner/{id}/statistik', [KuesionerController::class, 'statistik'])->name('kuesioner.statistik');

        // Route::get('/alumni', [AlumniController::class, 'tampilAlumni'])->name('admin.dashboard');
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

// Route::put('/alumni/{nim}', [AdminController::class, 'update'])->name('alumni.update');
Route::put('/admin/update-alumni', [AdminController::class, 'updateAlumni'])->name('admin.update-alumni');

Route::post('/session/destroy', action: [KuesionerController::class, 'destroySession'])->name('session.destroy');