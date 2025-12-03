@extends('layouts.dashboard')

@section('title', 'Laporan Penjualan Apotek')
@section('dashboard-title', 'Laporan Penjualan Apotek')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Filter Periode Laporan</h2>
        
        <form method="GET" action="{{ route('kasir-apotek.laporan') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode</label>
                <select name="period" id="period_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            
            <div class="flex-1 min-w-48 {{ $period != 'custom' ? 'hidden' : '' }}" id="custom_start_wrapper">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="custom_start" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            
            <div class="flex-1 min-w-48 {{ $period != 'custom' ? 'hidden' : '' }}" id="custom_end_wrapper">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="custom_end" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500" value="{{ $endDate->format('Y-m-d') }}">
            </div>

            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-md hover:bg-teal-700 transition-colors font-medium">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Lihat Laporan
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                Laporan Penjualan Apotek ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})
            </h3>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm font-medium no-print">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Laporan
            </button>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-teal-50 p-4 rounded-lg border-l-4 border-teal-600">
                    <p class="text-sm text-gray-600">Total Penjualan Bersih</p>
                    <p class="text-2xl font-bold text-teal-600 mt-1">Rp {{ number_format($laporan['total_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
                    <p class="text-sm text-gray-600">Total Resep Terlayani</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($laporan['transactions']->count()) }}</p>
                </div>
            </div>

            <div class="border p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Penjualan Berdasarkan Metode Pembayaran</h4>
                <div class="grid grid-cols-3 gap-4">
                    @foreach($laporan['by_payment_method'] as $method => $data)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">{{ $method }} ({{ $data['count'] }}x)</p>
                        <p class="font-bold text-lg text-gray-900">Rp {{ number_format($data['total'], 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="border p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Rincian Harian</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumlah Transaksi</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($laporan['daily_breakdown'] as $date => $data)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-sm text-gray-700">{{ $data['count'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-right font-bold text-sm text-teal-600">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3 border-b pb-2">Detail Setiap Transaksi Obat</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID Resep</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pasien</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumlah Bayar</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @foreach($laporan['transactions'] as $transaction)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-500">{{ \Carbon\Carbon::parse($transaction->tanggal_bayar)->format('d/m H:i') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap font-medium text-teal-600">#{{ $transaction->tagihan_id }}</td>
                                <td class="px-4 py-2 text-gray-900">{{ $transaction->tagihan->pasien->nama ?? 'N/A' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-right font-bold text-teal-600">Rp {{ number_format($transaction->jumlah_bayar, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $transaction->metode_pembayaran }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('period_filter').addEventListener('change', function() {
        const isCustom = this.value === 'custom';
        document.getElementById('custom_start_wrapper').classList.toggle('hidden', !isCustom);
        document.getElementById('custom_end_wrapper').classList.toggle('hidden', !isCustom);
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('period_filter').dispatchEvent(new Event('change'));
    });
</script>
@endsection