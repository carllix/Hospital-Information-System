@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Selamat Datang, <span>{{ $kasir->nama_lengkap }}!</span>
        </h1>
        <p class="text-gray-600 mt-1">Kelola pembayaran pasien</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-pink-600 mt-1">Rp {{ number_format($todayStats['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-pink-600 mt-1">{{ $todayStats['jumlah_transaksi'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Tagihan Pending</p>
                    <p class="text-2xl font-bold text-pink-600 mt-1">{{ $todayStats['tagihan_pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Siap Dibuatkan Tagihan</p>
                    <p class="text-2xl font-bold text-pink-600 mt-1">{{ $todayStats['pasien_siap_tagihan'] }}</p>
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
                                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                                        <span class="text-pink-600 font-bold">{{ substr($pemeriksaan->pendaftaran->pasien->nama_lengkap ?? 'P', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                                        <p class="text-sm text-gray-500">{{ $pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($pemeriksaan->resep && $pemeriksaan->resep->status === 'selesai')
                                                <span class="px-2 py-0.5 bg-pink-100 text-pink-700 text-xs rounded-full">Obat</span>
                                            @endif
                                            @if($pemeriksaan->permintaanLab && $pemeriksaan->permintaanLab->where('status', 'selesai')->count() > 0)
                                                <span class="px-2 py-0.5 bg-pink-100 text-pink-700 text-xs rounded-full">Lab</span>
                                            @endif
                                            @if($pemeriksaan->tindakan_medis)
                                                <span class="px-2 py-0.5 bg-pink-100 text-pink-700 text-xs rounded-full">Tindakan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('kasir.form-tagihan', $pemeriksaan->pemeriksaan_id) }}" class="px-4 py-2 bg-pink-500 text-white text-sm font-medium rounded-lg hover:bg-pink-600 transition-colors inline-block">
                                    Buat Tagihan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($pemeriksaanSiapTagihan->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $pemeriksaanSiapTagihan->links() }}
                </div>
                @endif
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
                                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                                        <span class="text-pink-600 font-bold text-sm">#{{ $tagihan->tagihan_id }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $tagihan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                                        <p class="text-sm text-gray-500">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400">{{ $tagihan->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('kasir.detail', $tagihan->tagihan_id) }}" class="px-4 py-2 bg-pink-500 text-white text-sm font-medium rounded-lg hover:bg-pink-600 transition-colors">
                                    Bayar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($tagihanPending->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $tagihanPending->links() }}
                </div>
                @endif
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
