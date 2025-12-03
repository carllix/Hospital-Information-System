@extends('layouts.dashboard')

@section('title', 'Riwayat Transaksi Klinik')
@section('dashboard-title', 'Riwayat Transaksi Klinik')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-pink-50 p-4 rounded-lg text-center border-l-4 border-pink-500">
                <p class="text-sm text-gray-600">Total Pendapatan</p>
                <p class="text-xl font-bold text-pink-600 mt-1">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg text-center border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Jumlah Transaksi</p>
                <p class="text-xl font-bold text-green-600 mt-1">{{ number_format($summary['transaction_count']) }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg text-center border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Konsultasi</p>
                <p class="text-xl font-bold text-blue-600 mt-1">{{ number_format($summary['konsultasi_count']) }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg text-center border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">Tindakan</p>
                <p class="text-xl font-bold text-purple-600 mt-1">{{ number_format($summary['tindakan_count']) }}</p>
            </div>
        </div>
        
        <form method="GET" action="{{ route('kasir-klinik.riwayat') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-48">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500"
                       value="{{ $startDate }}">
            </div>
            <div class="flex-1 min-w-48">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500"
                       value="{{ $endDate }}">
            </div>
            <div class="flex-1 min-w-48">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Pasien/ID</label>
                <input type="text" name="search" id="search" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500"
                       value="{{ $search }}" placeholder="Nama Pasien atau ID Tagihan">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md transition-colors font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Transaksi Lunas</h3>
        </div>
        
        @if($riwayat->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Tagihan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($riwayat as $tagihan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-pink-600">#{{ $tagihan->tagihan_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($tagihan->pembayaran->first()->tanggal_bayar ?? $tagihan->updated_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $tagihan->pasien->nama_lengkap ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">RM: {{ $tagihan->pasien->no_rekam_medis ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                                    {{ $tagihan->jenis_tagihan == 'konsultasi' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucfirst($tagihan->jenis_tagihan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-sm text-green-600">
                                Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $tagihan->pembayaran->first()->metode_pembayaran ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('kasir-klinik.invoice', $tagihan->tagihan_id) }}" 
                                    class="text-pink-600 hover:text-pink-800 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Invoice
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $riwayat->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-lg font-medium">Tidak ada riwayat transaksi</p>
                <p class="text-sm">Riwayat transaksi lunas akan muncul di sini.</p>
            </div>
        @endif
    </div>
</div>
@endsection