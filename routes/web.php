<?php

use App\Http\Controllers\RekapController;
use App\Http\Middleware\AlumniMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\AuthAdmin;
use App\Http\Controllers\AuthAlumni;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpKernel\Profiler\Profile;
use App\Http\Middleware\CustomThrottleRequests;


Route::get('/', [AlumniController::class, 'index'])->name('alumni.index');
Route::get('/statistik', [AlumniController::class, 'statistik'])->name('alumni.statistik');
Route::get('/faq', [AlumniController::class, 'faq'])->name('alumni.faq');


// limiter login alumni
RateLimiter::for('login', function (Request $request) {
    $email = (string) $request->input('email');
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($email.$request->ip());
});

// Rute untuk alumni
// Route::middleware(['throttle:login'])->post('/login', [AuthAlumni::class, 'login']);
Route::post('/login', [AuthAlumni::class, 'login'])->middleware(CustomThrottleRequests::class);
Route::get('/login', [AuthAlumni::class, 'showLoginForm'])->name('alumni.login');
Route::post('/logoutalumni', [AuthAlumni::class, 'logout'])->name('alumni.logout');
// Protected Alumni Routes
Route::middleware([AlumniMiddleware::class])->group(function () {

    Route::get('/profil', [ProfileController::class, 'show'])->name('alumni.profil');
    Route::get('/profil/edit', [ProfileController::class, 'editProfil'])->name('alumni.profil.edit');
    Route::post('/profil/update', [ProfileController::class, 'updateProfil'])->name('alumni.profil.update');

    Route::resource('kuesioner', KuesionerController::class);
    Route::get('/kuesioner', [KuesionerController::class, 'AlumniKuesioner'])->name('kuesioner.alumni.index');
    Route::get('/kuesioner/{slug}/{halamanId}', [KuesionerController::class, 'AlumniKuesionerPage'])->name('kuesioner.alumni.page');
    Route::post('/kuesioner/submit', [KuesionerController::class, 'submit'])->name('kuesioner.alumni.submit');
    Route::get('/dashboard', [AlumniController::class, 'dashboard'])->name('alumni.dashboard');
    Route::post('/alumni/change-password', [AlumniController::class, 'changePassword'])->name('alumni.change-password');

});

// Rute untuk admin
Route::prefix('admin')->group(function () {
    // Admin authentication routes
    Route::get('/login', [AuthAdmin::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthAdmin::class, 'login']);
    // Route::post('/logout', [AuthAdmin::class, 'logout'])->name('admin.logout');

    // Protected admin routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'tampilAlumni'])->name('admin.dashboard');
        Route::resource('kuesioner', KuesionerController::class);
        Route::get('/kuesioner', [KuesionerController::class, 'index'])->name('kuesioner.index');
        // Route::get('/kuesioner/page/{id}', [KuesionerController::class, 'showPage'])->name('kuesioner.page');
        Route::get('/kuesioner/{slug}/{halamanId}', [KuesionerController::class, 'showPage'])->name('kuesioner.page');
        Route::get('/alldata', [KuesionerController::class, 'allData'])->name('kuesioner.admin.alldata');
        Route::get('/kuesioner/{id}', [KuesionerController::class, 'show'])->name('kuesioner.admin.show');
        Route::resource('kuesioner.pertanyaan', PertanyaanController::class)->shallow();
        Route::post('/kuesioner/{id}/submit', [KuesionerController::class, 'submit'])->name('kuesioner.admin.submit');
        Route::get('/kuesioner/{id}/edit', [KuesionerController::class, 'edit'])->name('kuesioner.admin.edit');
        Route::put('/kuesioner/{id}', [KuesionerController::class, 'update'])->name('kuesioner.admin.update');
        Route::post('kuesioner/saveChoice', [KuesionerController::class, 'saveChoice'])->name('kuesioner.saveChoice');

        Route::get('/createchart/{kuesionerId}', [KuesionerController::class, 'createChart'])->name('admin.chart.create');
        Route::get('/chart', [KuesionerController::class, 'chartIndex'])->name('admin.chart.index');
        Route::post('/chart/store', [KuesionerController::class, 'storeChartData'])->name('admin.chart.store');
        Route::get('/chart/{kuesionerId}', [KuesionerController::class, 'showChart'])->name('admin.chart.show');
        Route::delete('/chart/{chartId}', [KuesionerController::class, 'deleteChart'])->name('admin.chart.delete');

        Route::get('/status', [KuesionerController::class, 'createStatus'])->name('admin.status.create');
        Route::post('/status/store', [KuesionerController::class, 'storeStatusData'])->name('admin.status.store');

        // Admin dashboard routes
        Route::get('/alumni', [AdminController::class, 'index'])->name('admin.alumni.index');
        Route::get('/alumni/search', [AdminController::class, 'search'])->name('admin.alumni.search');
        Route::get('/alumni/filter', [AdminController::class, 'filter'])->name('admin.alumni.filter');
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/show', [RekapController::class, 'show'])->name('rekap.show');
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

Route::post('/logout', [AuthAdmin::class, 'logout'])->name('logout');

Route::post('/logoutAlumni', [AuthAlumni::class, 'logoutSession'])->name('logoutAlumni');
