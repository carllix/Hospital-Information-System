@extends('layouts.dashboard')

@section('title', 'Dashboard | Pendaftaran Ganesha Hospital')
@section('dashboard-title', 'Dashboard Staf Pendaftaran')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->staf->nama_lengkap }}!</h2>
                <p class="mt-2 text-gray-600">Kelola pendaftaran pasien dan antrian kunjungan</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pendaftaran Hari Ini -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Pendaftaran Hari Ini</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $pendaftaranHariIni }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <!-- Total Pasien -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pasien</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $totalPasien }}</p>
                <p class="text-xs text-gray-500 mt-1">Pasien terdaftar</p>
            </div>
        </div>

        <!-- Antrian Menunggu -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Antrian Menunggu</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $antrianMenunggu }}</p>
                <p class="text-xs text-gray-500 mt-1">Hari ini</p>
            </div>
        </div>

        <!-- Pendaftaran Bulan Ini -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Pendaftaran Saya</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $pendaftaranBulanIni }}</p>
                <p class="text-xs text-gray-500 mt-1">Bulan ini</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Pasien Baru -->
            <a href="{{ route('pendaftaran.pasien-baru') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Pasien Baru</p>
                    <p class="text-sm text-gray-600">Daftarkan pasien baru</p>
                </div>
            </a>

            <!-- Pendaftaran Kunjungan -->
            <a href="{{ route('pendaftaran.kunjungan') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Pendaftaran Kunjungan</p>
                    <p class="text-sm text-gray-600">Daftarkan kunjungan pasien</p>
                </div>
            </a>

            <!-- Data Pasien -->
            <a href="{{ route('pendaftaran.data-pasien') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Data Pasien</p>
                    <p class="text-sm text-gray-600">Lihat data pasien</p>
                </div>
            </a>

            <!-- Antrian -->
            <a href="{{ route('pendaftaran.antrian') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Kelola Antrian</p>
                    <p class="text-sm text-gray-600">Lihat & kelola antrian</p>
                </div>
            </a>

            <!-- Jadwal Dokter -->
            <a href="{{ route('pendaftaran.jadwal-dokter') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Jadwal Dokter</p>
                    <p class="text-sm text-gray-600">Lihat jadwal praktik dokter</p>
                </div>
            </a>

            <!-- Riwayat -->
            <a href="{{ route('pendaftaran.riwayat') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Riwayat Pendaftaran</p>
                    <p class="text-sm text-gray-600">Lihat riwayat pendaftaran</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection