@extends('layouts.dashboard')

@section('title', 'Riwayat Pemeriksaan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Pemeriksaan</h1>
            <p class="text-gray-500 mt-1">Daftar pasien yang telah selesai diperiksa</p>
        </div>
        
        <div class="relative w-full md:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm transition duration-150 ease-in-out shadow-sm" 
                   placeholder="Cari nama pasien atau No. RM...">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($riwayatPemeriksaan->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Belum ada riwayat</h3>
                <p class="text-gray-500 mt-1 max-w-sm">Anda belum melakukan pemeriksaan apapun. Riwayat pemeriksaan akan muncul di sini setelah Anda menangani pasien.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Periksa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diagnosa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Akhir</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($riwayatPemeriksaan as $pemeriksaan)
                            <tr class="hover:bg-pink-50/30 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm">
                                        <div class="p-2 bg-gray-50 rounded-lg mr-3 text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->isoFormat('D MMM YYYY') }}
                                            </p>
                                            <p class="text-gray-500 text-xs mt-0.5">
                                                Pukul {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $pemeriksaan->pasien->nama_lengkap }}</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                                {{ $pemeriksaan->pasien->no_rm }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($pemeriksaan->pasien->tanggal_lahir)->age }} Thn
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm text-gray-800 truncate" title="{{ $pemeriksaan->diagnosa }}">
                                            {{ Str::limit($pemeriksaan->diagnosa, 40) }}
                                        </p>
                                        @if($pemeriksaan->icd10_code)
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                ICD-10: {{ $pemeriksaan->icd10_code }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pemeriksaan->status_pasien === 'selesai_penanganan')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Selesai
                                        </span>
                                    @elseif($pemeriksaan->status_pasien === 'perlu_resep')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                            Resep Obat
                                        </span>
                                    @elseif($pemeriksaan->status_pasien === 'perlu_lab')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                            Cek Lab
                                        </span>
                                    @elseif($pemeriksaan->status_pasien === 'dirujuk')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5"></span>
                                            Rujukan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('dokter.detail-pemeriksaan', $pemeriksaan->pemeriksaan_id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-pink-600 hover:bg-pink-50 transition-all duration-200"
                                       title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($riwayatPemeriksaan->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $riwayatPemeriksaan->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection