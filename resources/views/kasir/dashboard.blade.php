@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Selamat Datang, <span class="text-pink-600">{{ $kasir->nama_lengkap }}</span>
        </h1>
        <p class="text-gray-600 mt-1">Dashboard Kasir - Kelola pembayaran pasien</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($todayStats['total_pendapatan'], 0, ',', '.') }}</p>
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
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todayStats['jumlah_transaksi'] }}</p>
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
                    <p class="text-sm text-gray-500">Tagihan Pending</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todayStats['tagihan_pending'] }}</p>
                </div>
                <div class="p-3 bg-amber-100 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Siap Dibuatkan Tagihan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todayStats['pasien_siap_tagihan'] }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pasien Siap Dibuatkan Tagihan -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Pasien Siap Dibuatkan Tagihan</h3>
                <p class="text-sm text-gray-600 mt-1">Pemeriksaan selesai yang belum ada tagihan</p>
            </div>

            @if($pemeriksaanSiapTagihan->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($pemeriksaanSiapTagihan as $pemeriksaan)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-purple-600 font-bold">{{ substr($pemeriksaan->pendaftaran->pasien->nama_lengkap ?? 'P', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                                        <p class="text-sm text-gray-500">{{ $pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($pemeriksaan->resep && $pemeriksaan->resep->status === 'selesai')
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">Obat</span>
                                            @endif
                                            @if($pemeriksaan->permintaanLab && $pemeriksaan->permintaanLab->where('status', 'selesai')->count() > 0)
                                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Lab</span>
                                            @endif
                                            @if($pemeriksaan->tindakan_medis)
                                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs rounded-full">Tindakan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('kasir.form-tagihan', $pemeriksaan->pemeriksaan_id) }}" class="px-4 py-2 bg-purple-500 text-white text-sm font-medium rounded-lg hover:bg-purple-600 transition-colors inline-block">
                                    Buat Tagihan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Tidak ada pasien yang menunggu tagihan</p>
                </div>
            @endif
        </div>

        <!-- Tagihan Pending (Belum Bayar) -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Tagihan Menunggu Pembayaran</h3>
                <p class="text-sm text-gray-600 mt-1">Tagihan yang sudah dibuat tapi belum lunas</p>
            </div>

            @if($tagihanPending->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($tagihanPending as $tagihan)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                                        <span class="text-amber-600 font-bold text-sm">#{{ $tagihan->tagihan_id }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $tagihan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                                        <p class="text-sm text-gray-500">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400">{{ $tagihan->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('kasir.detail', $tagihan->tagihan_id) }}" class="px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors">
                                    Bayar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t">
                    {{ $tagihanPending->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Tidak ada tagihan pending</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Transaksi Terakhir -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Transaksi Terakhir</h3>
                <p class="text-sm text-gray-500">5 transaksi terakhir yang sudah selesai</p>
            </div>
            <a href="{{ route('kasir.riwayat') }}" class="text-pink-600 hover:text-pink-700 text-sm font-medium">
                Lihat Semua
            </a>
        </div>

        @if($recentTransactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No. Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentTransactions as $trans)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-medium text-gray-800">#{{ $trans->tagihan_id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $trans->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $trans->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    Rp {{ number_format($trans->total_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full uppercase">
                                        {{ $trans->pembayaran->first()->metode_pembayaran ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $trans->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('kasir.invoice', $trans->tagihan_id) }}" class="text-pink-600 hover:text-pink-700 text-sm font-medium">
                                        Lihat Invoice
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-gray-500">Belum ada transaksi</p>
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

@if(session('info'))
    <x-toast type="info" :message="session('info')" />
@endif
@endsection
