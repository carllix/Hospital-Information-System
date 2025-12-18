@extends('layouts.dashboard')

@section('title', 'Invoice #' . $tagihan->tagihan_id)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Actions -->
    <div class="flex justify-between items-center no-print">
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Invoice
        </button>
    </div>

    <!-- Invoice -->
    <div class="bg-white rounded-lg shadow-md p-8" id="invoice">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
                    <p class="text-gray-600 mt-1">No. Kwitansi: <span class="font-mono font-semibold">{{ $pembayaranTerakhir->no_kwitansi ?? '-' }}</span></p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-bold text-pink-600">RS Hospital</h2>
                    <p class="text-sm text-gray-600">Jl. Kesehatan No. 123</p>
                    <p class="text-sm text-gray-600">Telp: (021) 123-4567</p>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-2">DITAGIHKAN KEPADA</h3>
                <p class="font-semibold text-gray-800">{{ $tagihan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                <p class="text-sm text-gray-600">No. RM: {{ $tagihan->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</p>
                <p class="text-sm text-gray-600">{{ $tagihan->pemeriksaan->pendaftaran->pasien->alamat ?? '-' }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-sm font-semibold text-gray-500 mb-2">INFORMASI INVOICE</h3>
                <p class="text-sm text-gray-600">No. Tagihan: <span class="font-semibold">#{{ $tagihan->tagihan_id }}</span></p>
                <p class="text-sm text-gray-600">Tanggal: <span class="font-semibold">{{ $pembayaranTerakhir->tanggal_bayar->format('d/m/Y') ?? now()->format('d/m/Y') }}</span></p>
                <p class="text-sm text-gray-600">Dokter: <span class="font-semibold">{{ $tagihan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}</span></p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-8">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-3 text-sm font-semibold text-gray-600">ITEM</th>
                        <th class="text-center py-3 text-sm font-semibold text-gray-600">QTY</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-600">HARGA</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-600">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($tagihan->detailTagihan as $detail)
                        <tr>
                            <td class="py-3">
                                <p class="text-sm text-gray-800">{{ $detail->nama_item }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($detail->jenis_item) }}</p>
                            </td>
                            <td class="py-3 text-center text-sm text-gray-600">{{ $detail->jumlah }}</td>
                            <td class="py-3 text-right text-sm text-gray-600">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-3 text-right text-sm font-semibold text-gray-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="border-t-2 border-gray-200 pt-4">
            <div class="flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span>TOTAL</span>
                        <span class="text-pink-600">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="mt-8 p-4 bg-green-50 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600">Status Pembayaran</p>
                    <p class="font-bold text-green-800 text-lg">LUNAS</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-green-600">Metode Pembayaran</p>
                    <p class="font-semibold text-green-800">{{ ucfirst($pembayaranTerakhir->metode_pembayaran ?? '-') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-green-600">Dibayar</p>
                    <p class="font-semibold text-green-800">Rp {{ number_format($pembayaranTerakhir->jumlah_bayar ?? 0, 0, ',', '.') }}</p>
                </div>
                @if($kembalian > 0)
                    <div class="text-right">
                        <p class="text-sm text-green-600">Kembalian</p>
                        <p class="font-semibold text-green-800">Rp {{ number_format($kembalian, 0, ',', '.') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>Terima kasih atas kepercayaan Anda</p>
            <p class="mt-1">Kasir: {{ $pembayaranTerakhir->kasir->nama_lengkap ?? 'Staff' }}</p>
            <p class="mt-2 text-xs">Invoice ini sah dan dibuat oleh sistem secara otomatis</p>
        </div>
    </div>
</div>

<style>
    @media print {
        @page { margin: 1cm; }
        body * { visibility: hidden; }
        #invoice, #invoice * { visibility: visible; }
        #invoice { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .shadow-md { box-shadow: none !important; }
    }
</style>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif
@endsection
