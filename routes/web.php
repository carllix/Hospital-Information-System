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
    Route::get('/pasien/pembayaran/{id}', [PasienController::class, 'pembayaranDetail'])->name('pasien.pembayaran.detail');
    Route::get('/pasien/rekam-medis', [PasienController::class, 'rekamMedis'])->name('pasien.rekam-medis');
    Route::get('/pasien/rekam-medis/{id}', [PasienController::class, 'rekamMedisDetail'])->name('pasien.rekam-medis.detail');
    Route::get('/pasien/health-monitoring', [PasienController::class, 'healthMonitoring'])->name('pasien.health-monitoring');
    Route::get('/pasien/profile', [PasienController::class, 'profile'])->name('pasien.profile');
    Route::get('/pasien/profile/edit', [PasienController::class, 'editProfile'])->name('pasien.profile.edit');
    Route::put('/pasien/profile', [PasienController::class, 'updateProfile'])->name('pasien.profile.update');

    // Pendaftaran Routes
    Route::get('/pendaftaran/dashboard', [PendaftaranController::class, 'dashboard'])->name('pendaftaran.dashboard');
    Route::get('/pendaftaran/pasien-baru', [PendaftaranController::class, 'pasienBaru'])->name('pendaftaran.pasien-baru');
    Route::post('/pendaftaran/pasien-baru', [PendaftaranController::class, 'storePasienBaru'])->name('pendaftaran.pasien-baru.store');
    Route::get('/pendaftaran/kunjungan', [PendaftaranController::class, 'kunjungan'])->name('pendaftaran.kunjungan');
    Route::post('/pendaftaran/search-pasien', [PendaftaranController::class, 'searchPasien'])->name('pendaftaran.search-pasien');
    Route::post('/pendaftaran/store', [PendaftaranController::class, 'storePendaftaran'])->name('pendaftaran.store');
    Route::get('/pendaftaran/data-pasien', [PendaftaranController::class, 'dataPasien'])->name('pendaftaran.data-pasien');
    Route::get('/pendaftaran/antrian', [PendaftaranController::class, 'antrian'])->name('pendaftaran.antrian');
    Route::patch('/pendaftaran/{id}/status', [PendaftaranController::class, 'updateStatus'])->name('pendaftaran.update-status');
    Route::get('/pendaftaran/jadwal-dokter', [PendaftaranController::class, 'jadwalDokter'])->name('pendaftaran.jadwal-dokter');
    Route::get('/pendaftaran/riwayat', [PendaftaranController::class, 'riwayat'])->name('pendaftaran.riwayat');
    Route::get('/pendaftaran/profile', [PendaftaranController::class, 'profile'])->name('pendaftaran.profile');
    Route::get('/pendaftaran/profile/edit', [PendaftaranController::class, 'editProfile'])->name('pendaftaran.profile.edit');
    Route::put('/pendaftaran/profile', [PendaftaranController::class, 'updateProfile'])->name('pendaftaran.profile.update');

    // Dokter Routes
    Route::prefix('dokter')->name('dokter.')->group(function () {
        Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dashboard');
        Route::get('/antrian', [DokterController::class, 'antrian'])->name('antrian');
        Route::patch('/panggil-pasien/{id}', [DokterController::class, 'panggilPasien'])->name('panggil-pasien');
        
        // Pemeriksaan
        Route::get('/pemeriksaan/{id}', [DokterController::class, 'formPemeriksaan'])->name('form-pemeriksaan');
        Route::post('/pemeriksaan', [DokterController::class, 'storePemeriksaan'])->name('store-pemeriksaan');
        
        // Resep
        Route::get('/resep/{pemeriksaanId}', [DokterController::class, 'formResep'])->name('form-resep');
        Route::post('/resep', [DokterController::class, 'storeResep'])->name('store-resep');
        
        // Lab
        Route::get('/lab/{pemeriksaanId}', [DokterController::class, 'formLab'])->name('form-lab');
        Route::post('/lab', [DokterController::class, 'storeLab'])->name('store-lab');
        
        // Rujukan
        Route::get('/rujukan/{pemeriksaanId}', [DokterController::class, 'formRujukan'])->name('form-rujukan');
        Route::post('/rujukan', [DokterController::class, 'storeRujukan'])->name('store-rujukan');
        
        // Riwayat & Detail
        Route::get('/riwayat', [DokterController::class, 'riwayat'])->name('riwayat');
        Route::get('/pemeriksaan/detail/{id}', [DokterController::class, 'detailPemeriksaan'])->name('detail-pemeriksaan');
        
        // Profile
        Route::get('/profile', [DokterController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [DokterController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [DokterController::class, 'updateProfile'])->name('profile.update');
    });

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