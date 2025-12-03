@extends('layouts.dashboard')

@section('title', 'Kasir Apotek | Ganesha Hospital')
@section('dashboard-title', 'Dashboard Kasir Apotek')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Kasir Apotek</h2>
                <p class="text-gray-600">Kelola pembayaran obat dan resep dengan mudah</p>
            </div>
            <div class="text-right text-gray-500">
                <p class="text-sm">{{ Carbon\Carbon::now()->format('l, d F Y') }}</p>
                <p class="font-semibold clock-update">{{ Carbon\Carbon::now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('kasir-apotek.dashboard') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-48">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" id="start_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                       value="{{ $startDate }}">
            </div>
            <div class="flex-1 min-w-48">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" id="end_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                       value="{{ $endDate }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md transition-colors font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </button>
                <a href="{{ route('kasir-apotek.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 13m4.356 2H3m10-7h5.414M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-teal-500">
            <div class="flex items-center">
                <div class="bg-teal-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Penjualan Obat</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($todayStats['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Resep Terlayani</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($todayStats['jumlah_transaksi']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Menunggu Pembayaran</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($todayStats['tagihan_pending']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v4m0 0h-6m6 0H9m10 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6m4-10a2 2 0 012 2v4m-2-6V9a2 2 0 00-2-2h-4a2 2 0 00-2 2v4"/></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Rata-rata Transaksi</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($todayStats['rata_rata_transaksi'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Resep Menunggu Pembayaran
                </h3>
            </div>
            <div class="p-6">
                @if($tagihanPending->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID Resep</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Pasien</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No. RM</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Total Obat</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihanPending as $tagihan)
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-teal-600">#{{ $tagihan->tagihan_id }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $tagihan->pasien->nama ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $tagihan->pasien->no_rm ?? '-' }}</td>
                                    <td class="px-4 py-3 font-bold text-teal-600">
                                        Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $tagihan->created_at->format('d/m H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('kasir-apotek.show', $tagihan->tagihan_id) }}" 
                                           class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded-md text-sm transition-colors font-medium">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            Bayar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $tagihanPending->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p>Tidak ada resep yang menunggu pembayaran</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Transaksi Terbaru
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentTransactions as $transaction)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $transaction->pasien->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    {{ $transaction->pembayaran->first()->tanggal_bayar ?? $transaction->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <span class="bg-teal-100 text-teal-800 px-2 py-1 rounded-full text-sm font-medium">
                                Rp {{ number_format($transaction->total_tagihan, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p>Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
                
                @if($recentTransactions->count() > 0)
                    <div class="mt-4 text-center">
                        <a href="{{ route('kasir-apotek.riwayat') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                            Lihat Semua Riwayat →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-lg shadow-md p-6 mt-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold mb-1">Aksi Cepat Apotek</h3>
                <p class="opacity-90">Kelola riwayat transaksi dan laporan penjualan obat</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('kasir-apotek.riwayat') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-md transition-colors font-medium">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat
                </a>
                <a href="{{ route('kasir-apotek.laporan') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-md transition-colors font-medium">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Laporan
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Success/Error Messages (using toast component - assumption based on Kasir Klinik) --}}
@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection