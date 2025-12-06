<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dokter\DokterController;
use App\Http\Controllers\Farmasi\FarmasiController;
use App\Http\Controllers\Kasir\KasirApotekController;
use App\Http\Controllers\Kasir\KasirKlinikController;
// use App\Http\Controllers\Kasir\KasirLabController;
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
    Route::get('/pasien/jadwal-dokter', [PasienController::class, 'jadwalDokter'])->name('pasien.jadwal-dokter');
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
    Route::prefix('farmasi')->name('farmasi.')->group(function () {
        Route::get('/dashboard', [FarmasiController::class, 'dashboard'])->name('dashboard');
        
        // Resep
        Route::get('/resep', [FarmasiController::class, 'daftarResep'])->name('daftar-resep');
        Route::get('/resep/{id}', [FarmasiController::class, 'detailResep'])->name('detail-resep');
        Route::patch('/resep/{id}/proses', [FarmasiController::class, 'prosesResep'])->name('proses-resep');
        Route::patch('/resep/{id}/selesaikan', [FarmasiController::class, 'selesaikanResep'])->name('selesaikan-resep');
        
        // Stok Obat
        Route::get('/stok-obat', [FarmasiController::class, 'stokObat'])->name('stok-obat');
        Route::get('/obat/tambah', [FarmasiController::class, 'tambahObat'])->name('tambah-obat');
        Route::post('/obat', [FarmasiController::class, 'storeObat'])->name('store-obat');
        Route::get('/obat/{id}/edit', [FarmasiController::class, 'editObat'])->name('edit-obat');
        Route::put('/obat/{id}', [FarmasiController::class, 'updateObat'])->name('update-obat');
        Route::patch('/obat/{id}/stok', [FarmasiController::class, 'updateStok'])->name('update-stok');
        Route::delete('/obat/{id}', [FarmasiController::class, 'deleteObat'])->name('delete-obat');
        
        // Laporan
        Route::get('/laporan', [FarmasiController::class, 'laporanResep'])->name('laporan-resep');
    });

    // Lab Routes
    Route::prefix('lab')->name('lab.')->group(function () {
        Route::get('/dashboard', [LabController::class, 'dashboard'])->name('dashboard');
        
        // Permintaan Lab
        Route::get('/permintaan', [LabController::class, 'daftarPermintaan'])->name('daftar-permintaan');
        Route::get('/permintaan/{id}', [LabController::class, 'detailPermintaan'])->name('detail-permintaan');
        Route::patch('/permintaan/{id}/ambil', [LabController::class, 'ambilPermintaan'])->name('ambil-permintaan');
        
        // Input Hasil Lab
        Route::get('/hasil/{id}', [LabController::class, 'formHasil'])->name('form-hasil');
        Route::post('/hasil', [LabController::class, 'storeHasil'])->name('store-hasil');
        
        // Riwayat & Laporan
        Route::get('/riwayat', [LabController::class, 'riwayat'])->name('riwayat');
        Route::get('/laporan', [LabController::class, 'laporan'])->name('laporan');
        
        // Profile
        Route::get('/profile', [LabController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [LabController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [LabController::class, 'updateProfile'])->name('profile.update');
    });

    // Kasir Klinik Routes
    Route::prefix('kasir-klinik')->name('kasir-klinik.')->middleware(['auth', 'role:kasir_klinik'])->group(function () {
        Route::get('/', [KasirKlinikController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [KasirKlinikController::class, 'dashboard'])->name('dashboard');
        Route::get('/riwayat', [KasirKlinikController::class, 'riwayat'])->name('riwayat');
        Route::get('/laporan', [KasirKlinikController::class, 'laporan'])->name('laporan');
        Route::get('/tagihan/{tagihan}', [KasirKlinikController::class, 'show'])->name('show');
        Route::post('/tagihan/{tagihan}/bayar', [KasirKlinikController::class, 'processPayment'])->name('processPayment');
        Route::get('/tagihan/{tagihan}/invoice', [KasirKlinikController::class, 'invoice'])->name('invoice');
    });

    // Kasir Apotek Routes
    Route::prefix('kasir-apotek')->name('kasir-apotek.')->middleware(['auth', 'role:kasir_apotek'])->group(function () {
        Route::get('/', [KasirApotekController::class, 'dashboard'])->name('dashboard');
        Route::get('/index', [KasirApotekController::class, 'dashboard'])->name('index');
        Route::get('/riwayat', [KasirApotekController::class, 'riwayat'])->name('riwayat');
        Route::get('/laporan', [KasirApotekController::class, 'laporan'])->name('laporan');
        Route::get('/tagihan/{tagihan}', [KasirApotekController::class, 'show'])->name('show');
        Route::post('/tagihan/{tagihan}/bayar', [KasirApotekController::class, 'processPayment'])->name('processPayment');
        Route::get('/tagihan/{tagihan}/invoice', [KasirApotekController::class, 'invoice'])->name('invoice');
    });

    // Kasir Lab Routes
    // Route::prefix('kasir-lab')->name('kasir-lab.')->middleware(['auth', 'role:kasir_lab'])->group(function () {
    //     Route::get('/', [KasirLabController::class, 'dashboard'])->name('dashboard');
    //     Route::get('/index', [KasirApotekController::class, 'dashboard'])->name('index');
    //     Route::get('/riwayat', [KasirLabController::class, 'riwayat'])->name('riwayat');
    //     Route::get('/laporan', [KasirLabController::class, 'laporan'])->name('laporan');
    //     Route::get('/tagihan/{tagihan}', [KasirLabController::class, 'show'])->name('show');
    //     Route::post('/tagihan/{tagihan}/bayar', [KasirLabController::class, 'processPayment'])->name('processPayment');
    //     Route::get('/tagihan/{tagihan}/invoice', [KasirLabController::class, 'invoice'])->name('invoice');
    // });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('dokter', \App\Http\Controllers\Admin\DokterController::class);
        Route::resource('staf', \App\Http\Controllers\Admin\StafController::class);
        Route::resource('jadwal-dokter', \App\Http\Controllers\Admin\JadwalDokterController::class);
        Route::resource('obat', \App\Http\Controllers\Admin\ObatController::class);
        Route::resource('layanan', \App\Http\Controllers\Admin\LayananController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });
});