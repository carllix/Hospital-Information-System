@extends('layouts.dashboard')

@section('title', 'Daftar Permintaan Lab')

@section('content')
<div class="space-y-6">
    {{-- Header & Filter Section --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Permintaan Lab</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola permintaan pemeriksaan dari dokter</p>
        </div>

        {{-- Filter Form (Modern Style) --}}
        <form method="GET" action="{{ route('lab.daftar-permintaan') }}" class="flex items-center gap-2">
            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="appearance-none bg-white border border-gray-300 text-gray-700 py-2 pl-4 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent text-sm font-medium shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
                    <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </form>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">
                Total Permintaan: <span class="text-gray-900">{{ $permintaanList->total() }}</span>
            </h3>
            @if($status !== 'semua')
                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                    Filter: {{ ucfirst($status) }}
                </span>
            @endif
        </div>

        @if($permintaanList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/4">Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter Pengirim</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Pemeriksaan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($permintaanList as $permintaan)
                        <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                            {{-- Kolom Pasien --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    {{-- PERBAIKAN: Akses via pemeriksaan -> pendaftaran -> pasien --}}
                                    <span class="font-bold text-gray-900 text-sm">
                                        {{ $permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}
                                    </span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">
                                            {{-- Pastikan kolom ini sesuai database, biasanya no_rm atau no_rekam_medis --}}
                                            {{ $permintaan->pemeriksaan->pendaftaran->pasien->no_rm ?? $permintaan->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-400 mt-1">
                                        {{ $permintaan->tanggal_permintaan ? \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d M Y, H:i') : '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Kolom Dokter --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    {{-- PERBAIKAN: Akses via pemeriksaan -> pendaftaran -> jadwalDokter -> dokter --}}
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? 'Dokter Tidak Ditemukan' }}
                                    </span>
                                    <span class="text-xs text-gray-500 mt-0.5">
                                        {{ $permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->spesialis ?? 'Umum' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Kolom Jenis Pemeriksaan --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                        {{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}
                                    </span>
                                </div>
                                @if($permintaan->catatan_klinis)
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2" title="{{ $permintaan->catatan_klinis }}">
                                        <span class="font-medium">Catatan:</span> {{Str::limit($permintaan->catatan_klinis, 30)}}
                                    </p>
                                @endif
                            </td>

                            {{-- Kolom Status --}}
                            <td class="px-6 py-4">
                                @if($permintaan->status === 'menunggu')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                        Menunggu
                                    </span>
                                @elseif($permintaan->status === 'diproses')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                        Diproses
                                    </span>
                                @elseif($permintaan->status === 'selesai')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('lab.detail-permintaan', $permintaan->permintaan_lab_id) }}" 
                                       class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200"
                                       title="Lihat Detail & Proses">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Modern --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $permintaanList->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                <div class="bg-gray-50 p-4 rounded-full mb-3">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h3 class="text-gray-900 font-medium">Belum ada permintaan</h3>
                <p class="text-gray-500 text-sm mt-1">Saat ini belum ada permintaan pemeriksaan lab baru.</p>
            </div>
        @endif
    </div>
</div>

{{-- Toast Notification --}}
@if(session('success') || session('error'))
    <x-toast :type="session('success') ? 'success' : 'error'" :message="session('success') ?? session('error')" />
@endif

@endsection