@extends('layouts.dashboard')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard Dokter</h1>
                <span class="px-2.5 py-0.5 bg-pink-50 text-pink-700 text-xs font-semibold rounded-md border border-pink-100">
                    {{ $dokter->spesialisasi ?? 'Dokter Umum' }}
                </span>
            </div>
            <p class="text-gray-500 mt-1 text-sm">Selamat bertugas, <span class="font-semibold text-gray-900">Dr. {{ $dokter->nama_lengkap }}</span> 👋</p>
        </div>

        {{-- Widget Waktu --}}
        <div class="flex items-center gap-4 bg-white px-5 py-2.5 rounded-xl shadow-sm border border-gray-200">
            <div class="text-right">
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                <p class="text-xl font-bold text-gray-900 leading-none">{{ now()->format('H:i') }} <span class="text-xs font-medium text-gray-400">WIB</span></p>
            </div>
            <div class="h-8 w-8 bg-pink-50 rounded-lg flex items-center justify-center text-pink-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card 1 --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200 hover:border-blue-300 transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Antrian Menunggu</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $antrianHariIni }}</h3>
                    <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-600">
                        Hari Ini
                    </span>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200 hover:border-green-300 transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pasien Selesai</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $pasienDitanganiHariIni }}</h3>
                    <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-[10px] font-medium bg-green-50 text-green-600">
                        Sudah Diperiksa
                    </span>
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200 hover:border-pink-300 transition-all duration-300 group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Pasien</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPasienBulanIni }}</h3>
                    <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-[10px] font-medium bg-pink-50 text-pink-600">
                        Bulan Ini
                    </span>
                </div>
                <div class="p-3 bg-pink-50 rounded-lg text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions / Menu Utama --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Menu Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Antrian Pasien --}}
            <a href="{{ route('dokter.antrian') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-pink-400 hover:bg-pink-50/50 transition-all group">
                <div class="p-3 bg-pink-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-900">Antrian Pasien</p>
                    <p class="text-sm text-gray-600">Lihat & kelola antrian</p>
                </div>
            </a>

            {{-- Riwayat Pemeriksaan --}}
            <a href="{{ route('dokter.riwayat') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-pink-400 hover:bg-pink-50/50 transition-all group">
                <div class="p-3 bg-pink-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-900">Riwayat Pemeriksaan</p>
                    <p class="text-sm text-gray-600">Lihat riwayat pemeriksaan</p>
                </div>
            </a>

            {{-- Profile Dokter --}}
            <a href="{{ route('dokter.profile') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-pink-400 hover:bg-pink-50/50 transition-all group">
                <div class="p-3 bg-pink-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-900">Profile Dokter</p>
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