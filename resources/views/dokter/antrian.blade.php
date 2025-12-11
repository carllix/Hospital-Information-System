@extends('layouts.dashboard')

@section('title', 'Antrian Pasien')

@section('content')
<div class="space-y-8">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Antrian Pasien</h1>
            <p class="text-gray-500 mt-2 flex items-center gap-2 text-sm font-medium">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
        
        {{-- Status Antrian --}}
        <div class="bg-white px-5 py-3 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-pink-500"></span>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Sisa Antrian</p>
                <p class="text-xl font-bold text-gray-900 leading-none mt-0.5">
                    {{ $antrianPasien->where('status', 'menunggu')->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        @if($antrianPasien->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                <div class="bg-gray-50 p-6 rounded-full mb-6">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum ada pasien</h3>
                <p class="text-gray-500 mt-1 max-w-sm mx-auto text-sm">Daftar antrian kosong.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            {{-- Header dibuat simple --}}
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
                                
                                {{-- 1. NO ANTRIAN: Kecil, Abu-abu, Transparan --}}
                                <td class="py-4 px-6 align-middle">
                                    <span class="text-gray-500 font-mono text-sm font-medium">
                                        {{ $antrian->nomor_antrian }}
                                    </span>
                                </td>

                                {{-- 2. DATA PASIEN: Bersih dengan Avatar Inisial Simple --}}
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

                                {{-- 3. KELUHAN: Teks biasa, warna abu gelap --}}
                                <td class="py-4 px-6 align-middle">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $antrian->keluhan ?? '-' }}
                                    </p>
                                </td>

                                {{-- 4. STATUS: Badge standar --}}
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

                                {{-- 5. AKSI: Tombol tetap jelas tapi ukurannya pas --}}
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