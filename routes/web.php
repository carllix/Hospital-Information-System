<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dokter\DokterController;
use App\Http\Controllers\Farmasi\FarmasiController;
use App\Http\Controllers\Kasir\KasirController;
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
    Route::prefix('pasien')->name('pasien.')->middleware(['auth', 'role:pasien'])->group(function () {
        Route::get('/dashboard', [PasienController::class, 'dashboard'])->name('dashboard');

        // Pendaftaran Kunjungan Online
        Route::get('/pendaftaran-kunjungan', [PasienController::class, 'pendaftaranKunjungan'])->name('pendaftaran-kunjungan');
        Route::post('/get-dokter', [PasienController::class, 'getDokterBySpesialisasi'])->name('get-dokter');
        Route::post('/get-jadwal', [PasienController::class, 'getJadwalByDokter'])->name('get-jadwal');
        Route::post('/pendaftaran-kunjungan', [PasienController::class, 'storePendaftaranKunjungan'])->name('pendaftaran-kunjungan.store');

        // Jadwal & Monitoring
        Route::get('/jadwal-kunjungan', [PasienController::class, 'jadwalKunjungan'])->name('jadwal-kunjungan');
        Route::get('/pembayaran', [PasienController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/pembayaran/{id}', [PasienController::class, 'pembayaranDetail'])->name('pembayaran.detail');
        Route::get('/rekam-medis', [PasienController::class, 'rekamMedis'])->name('rekam-medis');
        Route::get('/rekam-medis/{id}', [PasienController::class, 'rekamMedisDetail'])->name('rekam-medis.detail');
        Route::get('/health-monitoring', [PasienController::class, 'healthMonitoring'])->name('health-monitoring');
        Route::get('/wearable-data', [PasienController::class, 'getLatestWearableData'])->name('wearable-data');
        Route::get('/jadwal-dokter', [PasienController::class, 'jadwalDokter'])->name('jadwal-dokter');

        // Profile
        Route::get('/profile', [PasienController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [PasienController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [PasienController::class, 'updateProfile'])->name('profile.update');
    });

    // Pendaftaran Routes
    Route::prefix('pendaftaran')->name('pendaftaran.')->middleware(['auth', 'role:staf,pendaftaran'])->group(function () {
        Route::get('/dashboard', [PendaftaranController::class, 'dashboard'])->name('dashboard');
        Route::get('/pasien-baru', [PendaftaranController::class, 'pasienBaru'])->name('pasien-baru');
        Route::post('/pasien-baru', [PendaftaranController::class, 'storePasienBaru'])->name('pasien-baru.store');
        Route::get('/kunjungan', [PendaftaranController::class, 'kunjungan'])->name('kunjungan');
        Route::post('/search-pasien', [PendaftaranController::class, 'searchPasien'])->name('search-pasien');
        Route::post('/get-dokter', [PendaftaranController::class, 'getDokterBySpesialisasi'])->name('get-dokter');
        Route::post('/get-jadwal', [PendaftaranController::class, 'getJadwalByDokter'])->name('get-jadwal');
        Route::post('/store', [PendaftaranController::class, 'storePendaftaran'])->name('store');
        Route::get('/data-pasien', [PendaftaranController::class, 'dataPasien'])->name('data-pasien');
        Route::get('/antrian', [PendaftaranController::class, 'antrian'])->name('antrian');
        Route::patch('/{id}/status', [PendaftaranController::class, 'updateStatus'])->name('update-status');
        Route::get('/jadwal-dokter', [PendaftaranController::class, 'jadwalDokter'])->name('jadwal-dokter');
        Route::get('/riwayat', [PendaftaranController::class, 'riwayat'])->name('riwayat');
        Route::get('/profile', [PendaftaranController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [PendaftaranController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [PendaftaranController::class, 'updateProfile'])->name('profile.update');
    });

    // Dokter Routes
    Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'role:dokter'])->group(function () {
        Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dashboard');
        Route::get('/antrian', [DokterController::class, 'antrian'])->name('antrian');
        Route::patch('/panggil-pasien/{id}', [DokterController::class, 'panggilPasien'])->name('panggil-pasien');

        // Pemeriksaan
        Route::get('/antrian/pemeriksaan/{id}', [DokterController::class, 'formPemeriksaan'])->name('form-pemeriksaan');
        Route::post('/antrian/pemeriksaan', [DokterController::class, 'storePemeriksaan'])->name('store-pemeriksaan');

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
        Route::get('/riwayat/detail/{id}', [DokterController::class, 'detailPemeriksaan'])->name('detail-pemeriksaan');

        // Profile
        Route::get('/profile', [DokterController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [DokterController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [DokterController::class, 'updateProfile'])->name('profile.update');

        Route::get('/riwayat/monitoring/{pasienId}', [DokterController::class, 'monitoringPasien'])->name('monitoring-pasien');
        Route::get('/riwayat/monitoring/{pasienId}/data', [DokterController::class, 'getPasienWearableData'])->name('monitoring.data');
    });

    // Farmasi Routes
    Route::prefix('farmasi')->name('farmasi.')->middleware(['auth', 'role:staf,farmasi'])->group(function () {
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
    Route::prefix('lab')->name('lab.')->middleware(['auth', 'role:staf,laboratorium'])->group(function () {
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

    // ========================================
    // KASIR ROUTES - UNIFIED
    // ========================================
    Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'role:staf,kasir'])->group(function () {
        Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
        Route::post('/buat-tagihan/{pemeriksaanId}', [KasirController::class, 'buatTagihan'])->name('buat-tagihan');
        Route::get('/detail/{tagihanId}', [KasirController::class, 'detail'])->name('detail');
        Route::post('/bayar/{tagihanId}', [KasirController::class, 'prosesPembayaran'])->name('proses-pembayaran');
        Route::get('/invoice/{tagihanId}', [KasirController::class, 'invoice'])->name('invoice');
        Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('riwayat');
        Route::get('/laporan', [KasirController::class, 'laporan'])->name('laporan');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Dokter Routes with Jadwal
        Route::resource('dokter', \App\Http\Controllers\Admin\DokterController::class);
        Route::post('/dokter/{id}/jadwal', [\App\Http\Controllers\Admin\DokterController::class, 'storeJadwal'])->name('dokter.jadwal.store');
        Route::put('/dokter/{dokterId}/jadwal/{jadwalId}', [\App\Http\Controllers\Admin\DokterController::class, 'updateJadwal'])->name('dokter.jadwal.update');
        Route::delete('/dokter/{dokterId}/jadwal/{jadwalId}', [\App\Http\Controllers\Admin\DokterController::class, 'destroyJadwal'])->name('dokter.jadwal.destroy');

        Route::resource('staf', \App\Http\Controllers\Admin\StafController::class);
        Route::resource('jadwal-dokter', \App\Http\Controllers\Admin\JadwalDokterController::class);
        Route::resource('obat', \App\Http\Controllers\Admin\ObatController::class);
        Route::resource('layanan', \App\Http\Controllers\Admin\LayananController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::post('/users/{id}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    });
});
