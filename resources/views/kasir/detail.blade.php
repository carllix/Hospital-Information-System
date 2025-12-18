@extends('layouts.dashboard')

@section('title', 'Detail Tagihan #' . $tagihan->tagihan_id)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('kasir.dashboard') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Tagihan #{{ $tagihan->tagihan_id }}</h1>
            <p class="text-gray-600">{{ $tagihan->created_at->format('d F Y, H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Tagihan -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Pasien -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Pasien</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Pasien</p>
                        <p class="font-semibold text-gray-800">{{ $tagihan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. Rekam Medis</p>
                        <p class="font-semibold text-gray-800">{{ $tagihan->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dokter</p>
                        <p class="font-semibold text-gray-800">{{ $tagihan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pemeriksaan</p>
                        <p class="font-semibold text-gray-800">{{ $tagihan->pemeriksaan->tanggal_pemeriksaan->format('d/m/Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Tagihan -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Rincian Tagihan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Item</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Harga</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tagihan->detailTagihan as $detail)
                                <tr>
                                    <td class="px-6 py-4">
                                        @if($detail->jenis_item === 'konsultasi')
                                            <span class="px-2 py-1 bg-pink-100 text-pink-700 text-xs rounded-full">Konsultasi</span>
                                        @elseif($detail->jenis_item === 'tindakan')
                                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full">Tindakan</span>
                                        @elseif($detail->jenis_item === 'obat')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Obat</span>
                                        @elseif($detail->jenis_item === 'lab')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Lab</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $detail->nama_item }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 text-center">{{ $detail->jumlah }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-800">TOTAL</td>
                                <td class="px-6 py-4 text-right font-bold text-xl text-pink-600">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Riwayat Pembayaran -->
            @if($tagihan->pembayaran->count() > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Riwayat Pembayaran</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach($tagihan->pembayaran as $bayar)
                            <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-green-800">{{ $bayar->no_kwitansi }}</p>
                                    <p class="text-sm text-green-600">{{ $bayar->tanggal_bayar->format('d/m/Y H:i') }} - {{ ucfirst($bayar->metode_pembayaran) }}</p>
                                </div>
                                <p class="font-bold text-green-700">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Form Pembayaran / Status -->
        <div class="space-y-6">
            <!-- Status Badge -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status Tagihan</h3>
                @if($tagihan->status === 'lunas')
                    <div class="p-4 bg-green-100 rounded-lg text-center">
                        <svg class="w-12 h-12 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="font-bold text-green-800 text-lg">LUNAS</p>
                    </div>
                    <a href="{{ route('kasir.invoice', $tagihan->tagihan_id) }}" class="mt-4 block w-full px-4 py-3 bg-pink-500 text-white text-center font-semibold rounded-lg hover:bg-pink-600 transition-colors">
                        Lihat Invoice
                    </a>
                @else
                    <div class="p-4 bg-amber-100 rounded-lg text-center mb-4">
                        <svg class="w-12 h-12 text-amber-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="font-bold text-amber-800 text-lg">BELUM LUNAS</p>
                    </div>
                @endif
            </div>

            <!-- Form Pembayaran -->
            @if($tagihan->status !== 'lunas')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Proses Pembayaran</h3>

                    <form action="{{ route('kasir.proses-pembayaran', $tagihan->tagihan_id) }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Tagihan</label>
                                <p class="text-2xl font-bold text-pink-600">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bayar <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_bayar" required min="{{ $tagihan->total_tagihan }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                    placeholder="Masukkan jumlah pembayaran" value="{{ old('jumlah_bayar', $tagihan->total_tagihan) }}">
                                @error('jumlah_bayar')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select name="metode_pembayaran" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="tunai">Tunai</option>
                                    <option value="debit">Kartu Debit</option>
                                    <option value="kredit">Kartu Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                    <option value="asuransi">Asuransi</option>
                                </select>
                                @error('metode_pembayaran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan" rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                    placeholder="Catatan (opsional)">{{ old('catatan') }}</textarea>
                            </div>

                            <button type="submit" class="w-full px-4 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                Proses Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <a href="{{ route('kasir.dashboard') }}" class="block w-full px-4 py-3 bg-gray-100 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection
