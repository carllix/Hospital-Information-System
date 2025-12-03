@extends('layouts.dashboard')

@section('title', 'Laporan Resep')

@section('content')
<div class="space-y-8">
    
    {{-- Header & Filter Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Resep</h1>
            <p class="text-sm text-gray-500 mt-1">
                Rekapitulasi data resep obat periode 
                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</span>
            </p>
        </div>

        {{-- Inline Filter --}}
        <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-2">
            <form method="GET" action="{{ route('farmasi.laporan-resep') }}" class="flex items-center gap-2">
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
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white p-2 rounded-lg transition-colors shadow-sm" title="Terapkan Filter">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
            <div class="w-px h-6 bg-gray-200 mx-1"></div>
            <button onclick="window.print()" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Print Laporan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </button>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card Total --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Resep</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['total'] }}</h3>
                </div>
                <div class="p-3 bg-gray-100 text-gray-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-gray-600 bg-gray-50 w-fit px-2 py-1 rounded-lg">
                Semua Status
            </div>
        </div>

        {{-- Card Selesai --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Selesai</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['selesai'] }}</h3>
                </div>
                <div class="p-3 bg-green-100 text-green-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-green-600 bg-green-50 w-fit px-2 py-1 rounded-lg">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Obat Diserahkan
            </div>
        </div>

        {{-- Card Diproses --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Diproses</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['diproses'] }}</h3>
                </div>
                <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-blue-600 bg-blue-50 w-fit px-2 py-1 rounded-lg">
                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span> Sedang Diracik
            </div>
        </div>

        {{-- Card Menunggu --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $statistik['menunggu'] }}</h3>
                </div>
                <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-amber-600 bg-amber-50 w-fit px-2 py-1 rounded-lg">
                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span> Belum Diproses
            </div>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($resepList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Resep</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokter</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Apoteker</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @foreach($resepList as $index => $resep)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $resep->tanggal_resep->format('d/m/Y') }}
                                <span class="text-gray-400 text-xs block">{{ $resep->tanggal_resep->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded">#{{ $resep->resep_id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $resep->pasien->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $resep->pasien->no_rekam_medis }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $resep->dokter->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $resep->apoteker ? $resep->apoteker->nama_lengkap : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($resep->status == 'menunggu')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Menunggu</span>
                                @elseif($resep->status == 'diproses')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Diproses</span>
                                @elseif($resep->status == 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer Tabel --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-xs text-gray-500">
                <span>Total Data: {{ $resepList->count() }}</span>
                <span>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</span>
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-gray-900 font-medium">Tidak ada data resep</h3>
                <p class="text-gray-500 text-sm mt-1">Belum ada data resep tercatat pada periode bulan ini.</p>
            </div>
        @endif
    </div>
</div>

{{-- Print CSS --}}
<style>
    @media print {
        @page { margin: 1cm; size: landscape; }
        body { visibility: hidden; }
        .space-y-8 > * { visibility: visible; }
        .space-y-8 { position: absolute; left: 0; top: 0; width: 100%; }
        
        /* Elements to Hide */
        form, button, a, select, nav, aside, .no-print { display: none !important; }
        
        /* Layout Adjustments */
        .shadow-sm, .shadow-md { box-shadow: none !important; border: 1px solid #ddd !important; }
        .bg-gray-50 { background-color: #fff !important; }
        
        /* Table Styling */
        table { width: 100% !important; border-collapse: collapse; }
        th, td { border: 1px solid #ddd !important; padding: 6px !important; font-size: 10pt; }
        thead th { background-color: #f3f4f6 !important; color: #000 !important; font-weight: bold; }
        
        /* Badge Adjustments for B&W Print */
        .rounded-full { border: 1px solid #000; color: #000 !important; background: transparent !important; }
        
        /* Color Correction */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
</style>
@endsection