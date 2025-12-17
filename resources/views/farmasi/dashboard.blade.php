@extends('layouts.dashboard')

@section('title', 'Dashboard Farmasi')
@section('dashboard-title', 'Dashboard Apoteker')

@section('content')
<div class="space-y-8">
    
    <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-pink-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-60 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-50 rounded-full blur-2xl -ml-10 -mb-10 opacity-60 pointer-events-none"></div>

        <div class="relative z-10">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
                Selamat Datang, <span class="text-pink-600">{{ $apoteker->nama_lengkap }}</span> 👋
            </h2>
            <p class="mt-2 text-gray-500 text-lg">
                Siap mengelola <span class="font-semibold text-gray-700">{{ $resepMenunggu }} resep baru</span> hari ini?
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        
        @php
            $cardClass = "bg-white rounded-2xl p-5 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 border border-gray-50 group cursor-default";
        @endphp

        <div class="{{ $cardClass }}">
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-amber-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    @if($resepMenunggu > 0)
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $resepMenunggu }}</h3>
                </div>
            </div>
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Sedang Diproses</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $resepDiproses }}</h3>
                </div>
            </div>
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-emerald-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Selesai Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $resepSelesaiHariIni }}</h3>
                </div>
            </div>
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-orange-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12a8 8 0 11-16 0 8 8 0 0116 0zM12 9v2m0 4h.01"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $obatStokMenipis }}</h3>
                </div>
            </div>
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-rose-50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Obat Habis</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $obatHabis }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Antrian Resep Terbaru</h3>
                    <p class="text-xs text-gray-500 mt-1">Pasien sedang menunggu obat disiapkan</p>
                </div>
                <a href="{{ route('farmasi.daftar-resep', ['status' => 'menunggu']) }}" class="group flex items-center text-sm text-pink-600 hover:text-pink-700 font-semibold transition-colors">
                    Lihat Semua 
                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                @if($resepMenungguList->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 bg-white">
                            <th class="px-6 py-4">Info Resep</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4 text-center">Jml Obat</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($resepMenungguList as $resep)
                        <tr class="hover:bg-pink-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                        #{{ $resep->resep_id }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $resep->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $resep->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $resep->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $resep->tanggal_resep->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-gray-900">{{ $resep->detailResep->count() }}</span>
                                <span class="text-xs text-gray-400">item</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('farmasi.detail-resep', $resep->resep_id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-pink-50 text-pink-600 text-sm font-medium rounded-lg hover:bg-pink-600 hover:text-white transition-all duration-200 border border-pink-100 hover:border-pink-600">
                                    Proses
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Semua Terkendali!</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-xs">Tidak ada resep yang menunggu antrian saat ini.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <h3 class="text-lg font-bold text-gray-800 px-1">Akses Cepat</h3>
            
            <a href="{{ route('farmasi.daftar-resep') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-pink-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-pink-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-pink-100 text-pink-600 rounded-xl group-hover:bg-pink-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Daftar Semua Resep</h4>
                        <p class="text-xs text-gray-500 mt-1">Kelola riwayat & status</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('farmasi.stok-obat') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Inventori Obat</h4>
                        <p class="text-xs text-gray-500 mt-1">Cek stok & kadaluarsa</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('farmasi.laporan-resep') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:border-emerald-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Laporan Farmasi</h4>
                        <p class="text-xs text-gray-500 mt-1">Analisis penjualan & resep</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Notification Toasts --}}
@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif
@endsection