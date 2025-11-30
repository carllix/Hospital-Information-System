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

    Route::get('/pasien/dashboard', function () {
        return view('dashboard.pasien');
    })->name('pasien.dashboard');

    Route::get('/pendaftaran/dashboard', function () {
        return view('dashboard.pendaftaran');
    })->name('pendaftaran.dashboard');

    Route::get('/dokter/dashboard', function () {
        return view('dashboard.dokter');
    })->name('dokter.dashboard');

    Route::get('/farmasi/dashboard', function () {
        return view('dashboard.farmasi');
    })->name('farmasi.dashboard');

    Route::get('/lab/dashboard', function () {
        return view('dashboard.lab');
    })->name('lab.dashboard');

    Route::get('/kasir-klinik/dashboard', function () {
        return view('dashboard.kasir-klinik');
    })->name('kasir-klinik.dashboard');

    Route::get('/kasir-apotek/dashboard', function () {
        return view('dashboard.kasir-apotek');
    })->name('kasir-apotek.dashboard');

    Route::get('/kasir-lab/dashboard', function () {
        return view('dashboard.kasir-lab');
    })->name('kasir-lab.dashboard');
});
