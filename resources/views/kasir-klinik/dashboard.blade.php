@extends('layouts.dashboard')

@section('title', 'Kasir Klinik | Ganesha Hospital')
@section('dashboard-title', 'Dashboard Kasir Klinik')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Kasir Klinik</h2>
                <p class="text-gray-600">Kelola pembayaran dan transaksi klinik dengan mudah</p>
            </div>
            <div class="text-right text-gray-500">
                <p class="text-sm">{{ Carbon\Carbon::now()->format('l, d F Y') }}</p>
                <p class="font-semibold clock-update">{{ Carbon\Carbon::now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('kasir-klinik.dashboard') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-48">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" id="start_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                       value="{{ $startDate }}">
            </div>
            <div class="flex-1 min-w-48">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" id="end_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                       value="{{ $endDate }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md transition-colors font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </button>
                <a href="{{ route('kasir-klinik.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 13m4.356 2H3m10-7h5.414M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-pink-500">
            <div class="flex items-center">
                <div class="bg-pink-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($todayStats['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Jumlah Transaksi</p>
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
                    <p class="text-gray-500 text-sm">Tagihan Pending</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($todayStats['tagihan_pending']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v4m0 0h-6m6 0H9m10 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6m4-10a2 2 0 012 2v4m-2-6V9a2 2 0 00-2-2h-4a2 2 0 00-2 2v4"/></svg>
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
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tagihan Menunggu Pembayaran
                </h3>
            </div>
            <div class="p-6">
                @if($tagihanPending->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Pasien</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jenis</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Total</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihanPending as $tagihan)
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-pink-600">#{{ $tagihan->tagihan_id }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $tagihan->pasien->nama ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $tagihan->jenis_tagihan == 'konsultasi' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ ucfirst($tagihan->jenis_tagihan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-pink-600">
                                        Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $tagihan->created_at->format('d/m H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('kasir-klinik.show', $tagihan->tagihan_id) }}" 
                                           class="bg-pink-600 hover:bg-pink-700 text-white px-3 py-1 rounded-md text-sm transition-colors font-medium">
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
                        <p>Tidak ada tagihan yang menunggu pembayaran</p>
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
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $transaction->pembayaran->tanggal_bayar ?? $transaction->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">
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
                        <a href="{{ route('kasir-klinik.riwayat') }}" class="text-pink-600 hover:text-pink-700 text-sm font-medium">
                            Lihat Semua Riwayat →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Success/Error Messages (using component) --}}
@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif

<script>
    // Real-time clock update (assuming it's needed)
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const timeElements = document.querySelectorAll('.clock-update');
        timeElements.forEach(element => {
            element.textContent = timeString + ' WIB';
        });
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        updateClock();
        setInterval(updateClock, 1000);
    });
</script>
@endsection