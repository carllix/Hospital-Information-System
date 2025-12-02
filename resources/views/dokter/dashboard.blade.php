@extends('layouts.dashboard')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Dokter</h1>
            <p class="text-gray-600 mt-1">Selamat datang, Dr. {{ $dokter->nama_lengkap }}</p>
            <p class="text-sm text-gray-500">{{ $dokter->spesialisasi ?? 'Dokter Umum' }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            <p class="text-2xl font-bold text-pink-600">{{ now()->format('H:i') }}</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Antrian Hari Ini -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Antrian Hari Ini</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $antrianHariIni }}</h3>
                    <p class="text-blue-100 text-xs mt-1">Pasien menunggu</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pasien Ditangani Hari Ini -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Pasien Ditangani</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $pasienDitanganiHariIni }}</h3>
                    <p class="text-green-100 text-xs mt-1">Hari ini</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pasien Bulan Ini -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Pasien</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $totalPasienBulanIni }}</h3>
                    <p class="text-purple-100 text-xs mt-1">Bulan ini</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Antrian Pasien Hari Ini -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Antrian Pasien Hari Ini
            </h2>
        </div>
        
        <div class="p-6">
            @if($antrianPasien->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Tidak ada pasien dalam antrian</p>
                    <p class="text-gray-400 text-sm mt-1">Semua pasien sudah ditangani</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">No. Antrian</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Nama Pasien</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">No. RM</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Keluhan</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($antrianPasien as $antrian)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-pink-100 text-pink-600 font-bold">
                                            {{ $antrian->nomor_antrian }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $antrian->pasien->nama_lengkap }}</p>
                                            <p class="text-sm text-gray-500">{{ $antrian->pasien->jenis_kelamin }} • {{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} tahun</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">{{ $antrian->pasien->no_rm }}</td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-700 line-clamp-2">{{ $antrian->keluhan ?? '-' }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($antrian->status === 'menunggu')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Menunggu
                                            </span>
                                        @elseif($antrian->status === 'dipanggil')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Dipanggil
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            @if($antrian->status === 'menunggu')
                                                <form method="POST" action="{{ route('dokter.panggil-pasien', $antrian->pendaftaran_id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium">
                                                        Panggil
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('dokter.form-pemeriksaan', $antrian->pendaftaran_id) }}" class="px-3 py-1.5 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors text-sm font-medium">
                                                Periksa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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