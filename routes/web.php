<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dokter\DokterController;
use App\Http\Controllers\Farmasi\FarmasiController;
use App\Http\Controllers\Kasir\KasirApotekController;
use App\Http\Controllers\Kasir\KasirKlinikController;
use App\Http\Controllers\Kasir\KasirLabController;
use App\Http\Controllers\Lab\LabController;
use App\Http\Controllers\Pasien\PasienController;
use App\Http\Controllers\Pendaftaran\PendaftaranController;
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
    Route::get('/pasien/dashboard', [PasienController::class, 'dashboard'])->name('pasien.dashboard');
    Route::get('/pasien/pembayaran', [PasienController::class, 'pembayaran'])->name('pasien.pembayaran');
    Route::get('/pasien/rekam-medis', [PasienController::class, 'rekamMedis'])->name('pasien.rekam-medis');
    Route::get('/pasien/health-monitoring', [PasienController::class, 'healthMonitoring'])->name('pasien.health-monitoring');
    Route::get('/pasien/profile', [PasienController::class, 'profile'])->name('pasien.profile');
    Route::get('/pasien/profile/edit', [PasienController::class, 'editProfile'])->name('pasien.profile.edit');
    Route::put('/pasien/profile', [PasienController::class, 'updateProfile'])->name('pasien.profile.update');

    // Pendaftaran Routes
    Route::get('/pendaftaran/dashboard', [PendaftaranController::class, 'dashboard'])->name('pendaftaran.dashboard');
    Route::get('/pendaftaran/pasien-management', [PendaftaranController::class, 'pasienManagement'])->name('pendaftaran.pasien-management');

    // Dokter Routes
    Route::get('/dokter/dashboard', [DokterController::class, 'dashboard'])->name('dokter.dashboard');

    // Farmasi Routes
    Route::get('/farmasi/dashboard', [FarmasiController::class, 'dashboard'])->name('farmasi.dashboard');

    // Lab Routes
    Route::get('/lab/dashboard', [LabController::class, 'dashboard'])->name('lab.dashboard');

    // Kasir Klinik Routes
    Route::get('/kasir-klinik/dashboard', [KasirKlinikController::class, 'dashboard'])->name('kasir-klinik.dashboard');

    // Kasir Apotek Routes
    Route::get('/kasir-apotek/dashboard', [KasirApotekController::class, 'dashboard'])->name('kasir-apotek.dashboard');

    // Kasir Lab Routes
    Route::get('/kasir-lab/dashboard', [KasirLabController::class, 'dashboard'])->name('kasir-lab.dashboard');
});
