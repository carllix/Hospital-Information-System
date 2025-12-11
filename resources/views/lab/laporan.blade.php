@extends('layouts.dashboard')

@section('title', 'Laporan Lab')

@section('content')
<div class="space-y-8">
    
    {{-- Header & Filter Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Laboratorium</h1>
            <p class="text-sm text-gray-500 mt-1">
                Rekapitulasi data pemeriksaan periode 
                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</span>
            </p>
        </div>

        {{-- Inline Filter --}}
        <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-2">
            <form method="GET" action="{{ route('lab.laporan') }}" class="flex items-center gap-2">
                <select name="bulan" class="bg-gray-50 border-transparent focus:border-pink-500 focus:ring-pink-500 rounded-lg text-sm py-2 pl-3 pr-8 font-medium text-gray-700 cursor-pointer hover:bg-gray-100 transition-colors">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" class="bg-gray-50 border-transparent focus:border-pink-500 focus:ring-pink-500 rounded-lg text-sm py-2 pl-3 pr-8 font-medium text-gray-700 cursor-pointer hover:bg-gray-100 transition-colors">
                    @foreach(range(date('Y') - 2, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white p-2 rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
            <div class="w-px h-6 bg-gray-200 mx-1"></div>
            <button onclick="window.print()" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Print Laporan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </button>
        </div>
    </div>

    {{-- Statistik Cards (Revised: High Contrast Icons) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card Total --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Permintaan</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['total'] }}</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-blue-600 bg-blue-50 w-fit px-2 py-1 rounded-lg">
                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> Semua Status
            </div>
        </div>

        {{-- Card Selesai --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Selesai</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['selesai'] }}</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-xl text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-green-600 bg-green-50 w-fit px-2 py-1 rounded-lg">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Hasil Keluar
            </div>
        </div>

        {{-- Card Diproses --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Diproses</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['diproses'] }}</h3>
                </div>
                <div class="p-3 bg-purple-100 rounded-xl text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-purple-600 bg-purple-50 w-fit px-2 py-1 rounded-lg">
                <span class="w-2 h-2 bg-purple-500 rounded-full mr-2 animate-pulse"></span> Sedang berjalan
            </div>
        </div>

        {{-- Card Menunggu --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['menunggu'] }}</h3>
                </div>
                <div class="p-3 bg-amber-100 rounded-xl text-amber-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-amber-600 bg-amber-50 w-fit px-2 py-1 rounded-lg">
                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span> Belum diambil
            </div>
        </div>
    </div>

    {{-- Kategori Breakdown Grid --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Statistik Jenis Pemeriksaan</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $jenisPemeriksaanLabels = [
                    'darah_lengkap' => ['label' => 'Darah Lengkap', 'color' => 'bg-red-50 text-red-700 border-red-100'],
                    'urine' => ['label' => 'Urine', 'color' => 'bg-yellow-50 text-yellow-700 border-yellow-100'],
                    'gula_darah' => ['label' => 'Gula Darah', 'color' => 'bg-pink-50 text-pink-700 border-pink-100'],
                    'kolesterol' => ['label' => 'Kolesterol', 'color' => 'bg-orange-50 text-orange-700 border-orange-100'],
                    'radiologi' => ['label' => 'Radiologi', 'color' => 'bg-gray-50 text-gray-700 border-gray-200'],
                    'lainnya' => ['label' => 'Lainnya', 'color' => 'bg-blue-50 text-blue-700 border-blue-100'],
                ];
            @endphp
            @foreach($jenisPemeriksaanLabels as $key => $style)
                @php
                    $count = $perJenisPemeriksaan[$key] ?? 0;
                    $percentage = $statistik['total'] > 0 ? round(($count / $statistik['total']) * 100) : 0;
                @endphp
                <div class="flex flex-col items-center justify-center p-4 rounded-xl border {{ $style['color'] }} transition-transform hover:scale-105">
                    <span class="text-2xl font-bold">{{ $count }}</span>
                    <span class="text-xs font-medium mt-1">{{ $style['label'] }}</span>
                    <div class="w-full bg-white/50 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="h-full bg-current opacity-50" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($permintaanList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemeriksaan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Petugas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @foreach($permintaanList as $index => $permintaan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $permintaan->tanggal_permintaan->format('d/m/Y') }}
                                <span class="text-gray-400 text-xs block">{{ $permintaan->tanggal_permintaan->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{-- Mengambil nama via pemeriksaan -> pendaftaran -> pasien --}}
                                    {{ $permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{-- Mengambil No RM --}}
                                    {{ $permintaan->pemeriksaan->pendaftaran->pasien->no_rm ?? $permintaan->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $permintaan->petugasLab ? $permintaan->petugasLab->nama_lengkap : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($permintaan->status == 'menunggu')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Menunggu</span>
                                @elseif($permintaan->status == 'diproses')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">Diproses</span>
                                @elseif($permintaan->status == 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Placeholer --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 text-xs text-gray-500">
                Menampilkan seluruh data periode ini
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-gray-900 font-medium">Tidak ada data</h3>
                <p class="text-gray-500 text-sm mt-1">Belum ada pemeriksaan lab pada periode bulan ini.</p>
            </div>
        @endif
    </div>
</div>

{{-- Print Styles --}}
<style>
    @media print {
        body { visibility: hidden; }
        .space-y-8 > * { visibility: visible; }
        .space-y-8 { position: absolute; left: 0; top: 0; width: 100%; }
        form, button, a, select, nav, aside, .no-print { display: none !important; }
        .shadow-sm, .shadow-md, .shadow-lg { box-shadow: none !important; border: 1px solid #ddd !important; }
        .bg-gray-50 { background-color: #fff !important; }
        table { width: 100% !important; border-collapse: collapse; }
        th, td { border: 1px solid #ddd !important; padding: 8px !important; }
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>
@endsection