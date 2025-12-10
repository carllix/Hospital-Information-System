@extends('layouts.dashboard')

@section('title', 'Dashboard | Ganesha Hospital')
@section('dashboard-title', 'Dashboard Pasien')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->pasien->nama_lengkap }}!</h2>
                <p class="mt-2 text-gray-600">Kelola jadwal kunjungan dan pantau riwayat kesehatan Anda</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Jadwal Kunjungan Mendatang -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Jadwal Kunjungan</p>
                    <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $jadwalKunjunganMendatang }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kunjungan mendatang</p>
                </div>
                <div class="p-3 bg-white rounded-lg">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Kunjungan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Kunjungan</p>
                    <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $totalKunjungan }}</p>
                    <p class="text-xs text-gray-500 mt-1">Semua kunjungan</p>
                </div>
                <div class="p-3 bg-white rounded-lg">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Riwayat Pemeriksaan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Riwayat Pemeriksaan</p>
                    <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $riwayatPemeriksaan }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pemeriksaan selesai</p>
                </div>
                <div class="p-3 bg-white rounded-lg">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Tagihan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tagihan Belum Bayar</p>
                    <p class="text-3xl font-bold text-[#f56e9d] mt-2">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total belum dibayar</p>
                </div>
                <div class="p-3 bg-white rounded-lg">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Daftar Kunjungan -->
            <a href="{{ route('pasien.pendaftaran-kunjungan') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Daftar Kunjungan</p>
                    <p class="text-sm text-gray-600">Buat janji temu baru</p>
                </div>
            </a>

            <!-- Jadwal Kunjungan -->
            <a href="{{ route('pasien.jadwal-kunjungan') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Jadwal Kunjungan</p>
                    <p class="text-sm text-gray-600">Lihat jadwal kunjungan</p>
                </div>
            </a>

            <!-- Rekam Medis -->
            <a href="{{ route('pasien.rekam-medis') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Rekam Medis</p>
                    <p class="text-sm text-gray-600">Lihat riwayat pemeriksaan</p>
                </div>
            </a>

            <!-- Pembayaran -->
            <a href="{{ route('pasien.pembayaran') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Pembayaran</p>
                    <p class="text-sm text-gray-600">Lihat & bayar tagihan</p>
                </div>
            </a>

            <!-- Jadwal Dokter -->
            <a href="{{ route('pasien.jadwal-dokter') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Jadwal Dokter</p>
                    <p class="text-sm text-gray-600">Lihat jadwal praktik dokter</p>
                </div>
            </a>

            <!-- Health Monitoring -->
            <a href="{{ route('pasien.health-monitoring') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Health Monitoring</p>
                    <p class="text-sm text-gray-600">Monitor data wearable</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection