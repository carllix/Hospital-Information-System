@extends('layouts.dashboard')

@section('title', 'Dashboard Farmasi')
@section('dashboard-title', 'Dashboard Apoteker')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ $apoteker->nama_lengkap }}!</h2>
                <p class="mt-2 text-gray-600">Siap mengelola {{ $resepMenunggu }} resep baru hari ini</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Resep Menunggu -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Resep Menunggu</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $resepMenunggu }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu diproses</p>
            </div>
        </div>

        <!-- Sedang Diproses -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Sedang Diproses</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $resepDiproses }}</p>
                <p class="text-xs text-gray-500 mt-1">Dalam pengerjaan</p>
            </div>
        </div>

        <!-- Selesai Hari Ini -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Selesai Hari Ini</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $resepSelesaiHariIni }}</p>
                <p class="text-xs text-gray-500 mt-1">Resep selesai</p>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Stok Menipis</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $obatStokMenipis }}</p>
                <p class="text-xs text-gray-500 mt-1">Perlu restock</p>
            </div>
        </div>

        <!-- Obat Habis -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Obat Habis</p>
                <p class="text-3xl font-bold text-[#f56e9d] mt-2">{{ $obatHabis }}</p>
                <p class="text-xs text-gray-500 mt-1">Stok kosong</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Antrian Resep Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Antrian Resep Terbaru</h3>
                    <p class="text-xs text-gray-500 mt-1">Pasien sedang menunggu obat disiapkan</p>
                </div>
                <a href="{{ route('farmasi.daftar-resep', ['status' => 'menunggu']) }}" class="text-sm text-[#f56e9d] hover:text-[#d14a7a] font-semibold transition-colors">
                    Lihat Semua →
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
                                    class="px-3 py-2 text-xs bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium shadow-sm">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Semua Terkendali!</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-xs">Tidak ada resep yang menunggu antrian saat ini.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Utama</h3>
                <div class="grid grid-cols-1 gap-4">
                    <!-- Daftar Resep -->
                    <a href="{{ route('farmasi.daftar-resep') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                        <div class="p-3 bg-[#f56e9d] rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-800">Daftar Resep</p>
                            <p class="text-sm text-gray-600">Kelola riwayat & status resep</p>
                        </div>
                    </a>

                    <!-- Stok Obat -->
                    <a href="{{ route('farmasi.stok-obat') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#f56e9d] hover:bg-[#f56e9d]/5 transition-all group">
                        <div class="p-3 bg-[#f56e9d] rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-800">Stok Obat</p>
                            <p class="text-sm text-gray-600">Kelola inventori obat</p>
                        </div>
                    </a>
                </div>
            </div>
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