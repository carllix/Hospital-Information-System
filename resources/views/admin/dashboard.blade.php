@extends('layouts.dashboard')

@section('title', 'Dashboard | Admin Ganesha Hospital')
@section('dashboard-title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, Admin!</h2>
                <p class="mt-2 text-gray-600">Kelola sistem informasi rumah sakit</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Dokter -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Dokter</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $totalDokter }}</p>
                <p class="text-xs text-gray-500 mt-1">Dokter aktif</p>
            </div>
        </div>

        <!-- Total Staf -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Staf</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $totalStaf }}</p>
                <p class="text-xs text-gray-500 mt-1">Staf aktif</p>
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

        <!-- Pendaftaran Hari Ini -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Pendaftaran Hari Ini</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $pendaftaranHariIni }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <!-- Pending Resep -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Resep Pending</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $pendingResep }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu/Diproses</p>
            </div>
        </div>

        <!-- Pending Lab -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Permintaan Lab Pending</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $pendingLab }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu/Diproses</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Kelola Dokter -->
            <a href="{{ route('admin.dokter.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Kelola Dokter</p>
                    <p class="text-sm text-gray-600">Tambah, edit, hapus dokter</p>
                </div>
            </a>

            <!-- Kelola Staf -->
            <a href="{{ route('admin.staf.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Kelola Staf</p>
                    <p class="text-sm text-gray-600">Tambah, edit, hapus staf</p>
                </div>
            </a>

            <!-- Jadwal Dokter -->
            <a href="{{ route('admin.jadwal-dokter.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Jadwal Dokter</p>
                    <p class="text-sm text-gray-600">Kelola jadwal praktik dokter</p>
                </div>
            </a>

            <!-- Kelola Obat -->
            <a href="{{ route('admin.obat.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Kelola Obat</p>
                    <p class="text-sm text-gray-600">Tambah, edit, hapus obat</p>
                </div>
            </a>

            <!-- Kelola Layanan -->
            <a href="{{ route('admin.layanan.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Kelola Layanan</p>
                    <p class="text-sm text-gray-600">Tambah, edit, hapus layanan</p>
                </div>
            </a>

            <!-- Kelola User -->
            <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                <div class="p-3 bg-[#f56e9d] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Kelola Pengguna</p>
                    <p class="text-sm text-gray-600">Kelola akun pengguna</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection