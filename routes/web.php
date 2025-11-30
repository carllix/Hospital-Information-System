<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect(auth()->user()->getDashboardRoute());
    }
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Pasien Routes
    Route::get('/pasien/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    Route::get('/pasien/pembayaran', function () {
        return view('pasien.pembayaran');
    })->name('pasien.pembayaran');

    Route::get('/pasien/rekam-medis', function () {
        return view('pasien.rekam-medis');
    })->name('pasien.rekam-medis');

    Route::get('/pasien/health-monitoring', function () {
        return view('pasien.health-monitoring');
    })->name('pasien.health-monitoring');

    // Pendaftaran Routes
    Route::get('/pendaftaran/dashboard', function () {
        return view('pendaftaran.dashboard');
    })->name('pendaftaran.dashboard');

    Route::get('/pendaftaran/pasien-management', function () {
        return view('pendaftaran.pasien-management');
    })->name('pendaftaran.pasien-management');

    // Dokter Routes
    Route::get('/dokter/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    // Farmasi Routes
    Route::get('/farmasi/dashboard', function () {
        return view('farmasi.dashboard');
    })->name('farmasi.dashboard');

    // Lab Routes
    Route::get('/lab/dashboard', function () {
        return view('lab.dashboard');
    })->name('lab.dashboard');

    // Kasir Klinik Routes
    Route::get('/kasir-klinik/dashboard', function () {
        return view('kasir-klinik.dashboard');
    })->name('kasir-klinik.dashboard');

    // Kasir Apotek Routes
    Route::get('/kasir-apotek/dashboard', function () {
        return view('kasir-apotek.dashboard');
    })->name('kasir-apotek.dashboard');

    // Kasir Lab Routes
    Route::get('/kasir-lab/dashboard', function () {
        return view('kasir-lab.dashboard');
    })->name('kasir-lab.dashboard');
});
