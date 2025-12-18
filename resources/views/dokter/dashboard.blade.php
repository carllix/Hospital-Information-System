@extends('layouts.dashboard')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, Dr. {{ $dokter->nama_lengkap }}!</h2>
                <p class="mt-2 text-gray-600">{{ $dokter->spesialisasi ?? 'Dokter Umum' }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Antrian Menunggu -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Antrian Menunggu</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $antrianHariIni }}</p>
                <p class="text-xs text-gray-500 mt-1">Hari Ini</p>
            </div>
        </div>

        <!-- Pasien Selesai -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Pasien Selesai</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $pasienDitanganiHariIni }}</p>
                <p class="text-xs text-gray-500 mt-1">Sudah Diperiksa</p>
            </div>
        </div>

        <!-- Total Pasien -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pasien</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $totalPasienBulanIni }}</p>
                <p class="text-xs text-gray-500 mt-1">Bulan Ini</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Antrian Pasien -->
            <a href="{{ route('dokter.antrian') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Antrian Pasien</p>
                    <p class="text-sm text-gray-600">Lihat & kelola antrian</p>
                </div>
            </a>

            <!-- Riwayat Pemeriksaan -->
            <a href="{{ route('dokter.riwayat') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Riwayat Pemeriksaan</p>
                    <p class="text-sm text-gray-600">Lihat riwayat pemeriksaan</p>
                </div>
            </a>

            <!-- Profile Dokter -->
            <a href="{{ route('dokter.profile') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Profile Dokter</p>
                    <p class="text-sm text-gray-600">Lihat & edit profile</p>
                </div>
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
<x-toast type="error" :message="session('error')" />
@endif
@endsection