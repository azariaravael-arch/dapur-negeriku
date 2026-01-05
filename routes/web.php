<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\ProyekController;

// ===============================
// ROUTE AUTH (Bisa diakses tanpa login)
// ===============================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===============================
// ROUTE YANG BISA DIAKSES TANPA LOGIN
// ===============================
Route::resource('pengguna', PenggunaController::class);
Route::post('/pengguna/{id}/toggle', [PenggunaController::class, 'toggle'])->name('pengguna.toggle');

// ===============================
// DASHBOARD (Harus login)
// ===============================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // PRODUK ROUTE
    Route::get('/produk', function () {
        return view('produk.index');
    })->name('produk');

    // ROUTE KLIEN
    Route::resource('klien', KlienController::class);
    Route::post('/klien/{klien}/toggle', [KlienController::class, 'toggle'])->name('klien.toggle');

    // Redirect profil-klien ke klien
    Route::get('/profil-klien', function () {
        return redirect()->route('klien.index');
    })->name('profil-klien');

    // ROUTE PROYEK
    Route::resource('proyek', ProyekController::class);

    // ROUTE LAINNYA
    Route::get('/layanan', function () {
        return view('layanan.index');
    })->name('layanan');

    Route::get('/banner', function () {
        return view('banner.index');
    })->name('banner');

    Route::resource('banner', HeroSectionController::class)->names([
        'index' => 'banner.index',
        'create' => 'banner.create',
        'store' => 'banner.store',
        'edit' => 'banner.edit',
        'update' => 'banner.update',
        'destroy' => 'banner.destroy',
    ]);

    Route::get('/info-kontak', function () {
        return view('info-kontak.index');
    })->name('info-kontak');

    Route::get('/profil-perusahaan', function () {
        return view('profil-perusahaan.index');
    })->name('profil-perusahaan');
});

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Di dalam middleware auth (setelah route proyek)
Route::resource('layanan', \App\Http\Controllers\LayananController::class);
Route::post('/layanan/{layanan}/toggle', [\App\Http\Controllers\LayananController::class, 'toggle'])
    ->name('layanan.toggle');
