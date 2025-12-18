@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-gray-600">Rekapitulasi pendapatan periode {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</p>
        </div>

        <div class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-md">
            <form method="GET" action="{{ route('kasir.laporan') }}" class="flex items-center gap-2">
                <select name="bulan" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-500">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-500">
                    @foreach(range(date('Y') - 2, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
                    Filter
                </button>
            </form>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors no-print">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($statistik['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Jumlah Transaksi</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $statistik['jumlah_transaksi'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Rata-rata Transaksi</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">
                        Rp {{ $statistik['jumlah_transaksi'] > 0 ? number_format($statistik['total_pendapatan'] / $statistik['jumlah_transaksi'], 0, ',', '.') : 0 }}
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Periode</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F Y') }}</p>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- By Metode & By Jenis Item -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- By Metode Pembayaran -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Berdasarkan Metode Pembayaran</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($statistik['by_metode'] as $metode => $data)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800 uppercase">{{ $metode }}</p>
                            <p class="text-sm text-gray-500">{{ $data['count'] }} transaksi</p>
                        </div>
                        <p class="font-bold text-gray-800">Rp {{ number_format($data['total'], 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        <!-- By Jenis Item -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Berdasarkan Jenis Layanan</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($statistik['by_jenis_item'] as $jenis => $data)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            @if($jenis === 'konsultasi')
                                <span class="px-3 py-1 bg-pink-100 text-pink-700 text-sm rounded-full">Konsultasi</span>
                            @elseif($jenis === 'tindakan')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm rounded-full">Tindakan</span>
                            @elseif($jenis === 'obat')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm rounded-full">Obat</span>
                            @elseif($jenis === 'lab')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full">Lab</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ ucfirst($jenis) }}</span>
                            @endif
                            <p class="text-sm text-gray-500">{{ $data['count'] }} item</p>
                        </div>
                        <p class="font-bold text-gray-800">Rp {{ number_format($data['total'], 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Detail Transaksi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Detail Transaksi</h3>
        </div>
        @if($pembayaranList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No. Kwitansi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pembayaranList as $index => $pembayaran)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm">{{ $pembayaran->no_kwitansi }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-800">{{ $pembayaran->tagihan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full uppercase">{{ $pembayaran->metode_pembayaran }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $pembayaran->kasir->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800 text-right">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-right font-bold text-gray-800">TOTAL</td>
                            <td class="px-6 py-4 text-right font-bold text-xl text-green-600">Rp {{ number_format($statistik['total_pendapatan'], 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-gray-500">Tidak ada transaksi pada periode ini</p>
            </div>
        @endif
    </div>
</div>

<style>
    @media print {
        @page { margin: 1cm; size: landscape; }
        .no-print { display: none !important; }
        .shadow-sm { box-shadow: none !important; }
    }
</style>
@endsection
