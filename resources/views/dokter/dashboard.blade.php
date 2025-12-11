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

    {{-- Table Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Antrian Hari Ini</h2>
        </div>
        
        @if($antrianPasien->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-gray-900 font-medium">Tidak ada antrian</h3>
                <p class="text-gray-500 text-sm mt-1">Semua pasien sudah ditangani.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Keluhan</th>
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($antrianPasien as $antrian)
                            <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                
                                {{-- No Antrian Simple --}}
                                <td class="py-4 px-6 align-middle">
                                    <span class="text-gray-500 font-mono text-sm font-medium">
                                        {{ $antrian->nomor_antrian }}
                                    </span>
                                </td>

                                {{-- Data Pasien dengan Avatar --}}
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-xs shrink-0">
                                            {{ substr($antrian->pasien->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">{{ $antrian->pasien->nama_lengkap }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $antrian->pasien->no_rm }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Keluhan --}}
                                <td class="py-4 px-6 align-middle">
                                    <p class="text-sm text-gray-600 line-clamp-1">
                                        {{ $antrian->keluhan ?? '-' }}
                                    </p>
                                </td>

                                {{-- Status --}}
                                <td class="py-4 px-6 align-middle">
                                    @if($antrian->status === 'menunggu')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                            Menunggu
                                        </span>
                                    @elseif($antrian->status === 'dipanggil')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            Dipanggil Staf
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi (Tanpa tombol Panggil) --}}
                                <td class="py-4 px-6 align-middle text-right">
                                    <a href="{{ route('dokter.form-pemeriksaan', $antrian->pendaftaran_id) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-pink-600 rounded-lg hover:bg-pink-700 transition-colors shadow-sm">
                                        <span>Periksa</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection