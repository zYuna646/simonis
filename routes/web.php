<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

// Route autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route dashboard dengan middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Route admin dengan middleware role
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Route manajemen pengguna
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);
    
    // Route manajemen kelas
    Route::resource('kelas', \App\Http\Controllers\KelasController::class, ['as' => 'admin']);
    
    // Route manajemen mata pelajaran
    Route::resource('mata-pelajaran', \App\Http\Controllers\MataPelajaranController::class, ['as' => 'admin']);
});

// Route dashboard untuk semua role
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});

// Route untuk admin dan guru
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Route manajemen kelas dengan tampilan card
    Route::get('/kelas-card', [\App\Http\Controllers\KelasController::class, 'card'])->name('admin.kelas-card');
    
    // Route manajemen siswa per kelas
    Route::get('/kelas/{id}/siswa', [\App\Http\Controllers\Admin\KelasSiswaController::class, 'index'])->name('admin.kelas.siswa.index');
    Route::get('/kelas/{id}/siswa/create', [\App\Http\Controllers\Admin\KelasSiswaController::class, 'create'])->name('admin.kelas.siswa.create');
    Route::post('/kelas/{id}/siswa', [\App\Http\Controllers\Admin\KelasSiswaController::class, 'store'])->name('admin.kelas.siswa.store');
    Route::delete('/kelas/{kelasId}/siswa/{siswaId}', [\App\Http\Controllers\Admin\KelasSiswaController::class, 'destroy'])->name('admin.kelas.siswa.destroy');
    
    // Rute untuk kehadiran
     Route::get('/kelas/{kelas}/siswa/{user}/kehadiran', [\App\Http\Controllers\Admin\KehadiranController::class, 'index'])->name('admin.kelas.siswa.kehadiran.index');
     Route::get('/kelas/{kelas}/siswa/{user}/kehadiran/create', [\App\Http\Controllers\Admin\KehadiranController::class, 'create'])->name('admin.kelas.siswa.kehadiran.create');
     Route::post('/kelas/{kelas}/siswa/{user}/kehadiran', [\App\Http\Controllers\Admin\KehadiranController::class, 'store'])->name('admin.kelas.siswa.kehadiran.store');
     Route::get('/kelas/{kelas}/siswa/{user}/kehadiran/{kehadiran}/edit', [\App\Http\Controllers\Admin\KehadiranController::class, 'edit'])->name('admin.kelas.siswa.kehadiran.edit');
     Route::put('/kelas/{kelas}/siswa/{user}/kehadiran/{kehadiran}', [\App\Http\Controllers\Admin\KehadiranController::class, 'update'])->name('admin.kelas.siswa.kehadiran.update');
     Route::delete('/kelas/{kelas}/siswa/{user}/kehadiran/{kehadiran}', [\App\Http\Controllers\Admin\KehadiranController::class, 'destroy'])->name('admin.kelas.siswa.kehadiran.destroy');
     
     // Rute untuk nilai
     Route::get('/kelas/{kelas}/siswa/{user}/nilai', [\App\Http\Controllers\Admin\NilaiController::class, 'index'])->name('admin.kelas.siswa.nilai.index');
     Route::get('/kelas/{kelas}/siswa/{user}/nilai/create', [\App\Http\Controllers\Admin\NilaiController::class, 'create'])->name('admin.kelas.siswa.nilai.create');
     Route::post('/kelas/{kelas}/siswa/{user}/nilai', [\App\Http\Controllers\Admin\NilaiController::class, 'store'])->name('admin.kelas.siswa.nilai.store');
     Route::get('/kelas/{kelas}/siswa/{user}/nilai/{nilai}/edit', [\App\Http\Controllers\Admin\NilaiController::class, 'edit'])->name('admin.kelas.siswa.nilai.edit');
     Route::put('/kelas/{kelas}/siswa/{user}/nilai/{nilai}', [\App\Http\Controllers\Admin\NilaiController::class, 'update'])->name('admin.kelas.siswa.nilai.update');
     Route::delete('/kelas/{kelas}/siswa/{user}/nilai/{nilai}', [\App\Http\Controllers\Admin\NilaiController::class, 'destroy'])->name('admin.kelas.siswa.nilai.destroy');
});
