@extends('layouts.dashboard')

@section('title', 'Antrian Pasien')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Antrian Pasien</h1>
            <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
        
        <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm flex items-center gap-3">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-pink-500"></span>
            </span>
            <span class="text-sm font-medium text-gray-600">
                Sisa Antrian: <span class="text-gray-900 font-bold">{{ $antrianPasien->where('status', 'menunggu')->count() }}</span>
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($antrianPasien->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                <div class="bg-gray-50 p-4 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada pasien</h3>
                <p class="text-gray-500 text-sm mt-1 max-w-sm">Daftar antrian masih kosong. Pasien baru akan muncul di sini setelah mendaftar.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">No</th>
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Data Pasien</th>
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keluhan Utama</th>
                            <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @foreach($antrianPasien as $antrian)
                            <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-pink-50 text-pink-600 font-bold text-base border border-pink-100 group-hover:bg-pink-500 group-hover:text-white group-hover:border-pink-500 transition-colors">
                                        {{ $antrian->nomor_antrian }}
                                    </div>
                                </td>

                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 text-sm">{{ $antrian->pasien->nama_lengkap }}</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">{{ $antrian->pasien->no_rm }}</span>
                                            <span class="text-xs text-gray-400">•</span>
                                            <span class="text-xs text-gray-500">{{ $antrian->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} ({{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} th)</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6">
                                    <p class="text-sm text-gray-600 line-clamp-2 max-w-xs leading-relaxed">
                                        {{ $antrian->keluhan ?? '-' }}
                                    </p>
                                </td>

                                <td class="py-4 px-6">
                                    @if($antrian->status === 'menunggu')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @elseif($antrian->status === 'dipanggil')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                                            Dipanggil
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($antrian->status === 'menunggu')
                                            <form method="POST" action="{{ route('dokter.panggil-pasien', $antrian->pendaftaran_id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-pink-500 transition-all shadow-sm">
                                                    Panggil
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('dokter.form-pemeriksaan', $antrian->pendaftaran_id) }}" 
                                           class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg hover:from-pink-600 hover:to-pink-700 shadow-md shadow-pink-200 transition-all transform hover:-translate-y-0.5">
                                            <span>Periksa</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
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

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif

@endsection