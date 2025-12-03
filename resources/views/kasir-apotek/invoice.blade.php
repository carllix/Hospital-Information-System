@extends('layouts.dashboard')

@section('title', 'Struk Pembayaran Apotek')

@section('content')
<div class="container-fluid">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden print-area">
        <div class="bg-gray-50 p-6 text-center border-b-4 border-teal-600">
            <div class="w-16 h-16 bg-teal-600 rounded-full flex items-center justify-center mx-auto mb-3 text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h-4v-4h4v4zm-7 0H3v-4h4v4zm13-8h-4V8h4v4zm-7 0H3V8h4v4z"/></svg>
            </div>
            <h4 class="font-bold text-xl text-gray-900 mb-1">GANESHA HOSPITAL</h4>
            <p class="text-sm text-gray-600 mb-1">Jl. Kesehatan No. 123, Bandung, Jawa Barat</p>
            <p class="text-sm text-gray-600">Telp: (022) 1234567 | Email: info@ganesha.co.id</p>
        </div>

        <div class="bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">
                        <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        RESEP APOTEK #{{ $tagihan->tagihan_id }}
                    </h3>
                    <p class="opacity-90 text-sm mt-1">Pembayaran Berhasil Diproses</p>
                </div>
                <div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold shadow-md">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        LUNAS
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h6 class="font-bold text-teal-600 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Informasi Pasien
                    </h6>
                    <p class="text-sm"><strong>Nama:</strong> {{ $tagihan->pasien->nama ?? 'N/A' }}</p>
                    <p class="text-sm"><strong>No. RM:</strong> {{ $tagihan->pasien->no_rm ?? '-' }}</p>
                    @if($tagihan->pasien->tanggal_lahir ?? false)
                        <p class="text-sm"><strong>Tgl Lahir:</strong> {{ \Carbon\Carbon::parse($tagihan->pasien->tanggal_lahir)->format('d/m/Y') }}</p>
                    @endif
                </div>
                <div>
                    <h6 class="font-bold text-teal-600 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Informasi Transaksi
                    </h6>
                    <p class="text-sm"><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($pembayaranTerakhir->tanggal_bayar)->format('d/m/Y H:i:s') }}</p>
                    <p class="text-sm"><strong>Jenis Tagihan:</strong> 
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-teal-100 text-teal-800">
                            {{ strtoupper($tagihan->jenis_tagihan) }}
                        </span>
                    </p>
                    <p class="text-sm"><strong>Kasir:</strong> {{ auth()->user()->name ?? 'System' }}</p>
                </div>
            </div>

            <h6 class="font-bold text-teal-600 mb-3 border-t pt-4 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Detail Obat & Aturan Pakai
            </h6>
            <div class="overflow-x-auto border rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-teal-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Deskripsi Obat & Aturan Pakai</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold" style="width: 10%;">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold" style="width: 15%;">Harga Satuan</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold" style="width: 15%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tagihan->detailTagihan as $detail)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <strong>{{ $detail->deskripsi_item }}</strong>
                                @if($detail->catatan ?? false)
                                    <div class="bg-teal-50 text-teal-800 text-xs p-2 mt-1 rounded-md border-l-2 border-teal-600">
                                        <strong class="mr-1">Aturan Pakai:</strong> {{ $detail->catatan }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">{{ $detail->kuantitas }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-700">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-sm text-teal-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-50">
                            <th colspan="3" class="px-4 py-3 text-right text-base font-bold text-gray-900">TOTAL BIAYA OBAT</th>
                            <th class="px-4 py-3 text-right text-lg font-extrabold text-teal-600">
                                Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 bg-green-500 text-white p-5 rounded-lg shadow-lg">
                <h6 class="font-bold mb-3 flex items-center text-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Ringkasan Pembayaran
                </h6>
                <div class="grid grid-cols-2 gap-4 text-sm font-medium">
                    <div>
                        <p class="opacity-90">Total Tagihan:</p>
                        <p class="font-bold text-lg">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="opacity-90">Metode Pembayaran:</p>
                        <p class="font-bold text-lg flex items-center">
                            @if($pembayaranTerakhir->metode_pembayaran == 'Tunai')
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9a1 1 0 00-1-1H4a1 1 0 00-1 1v6a1 1 0 001 1h7a1 1 0 001-1v-1m0-8h6a1 1 0 011 1v6a1 1 0 01-1 1h-6"/></svg>
                            @elseif($pembayaranTerakhir->metode_pembayaran == 'Debit')
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            @else
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1.5M9 21h1.5M17 14h.01M17 21h.01M12 21h.01"/></svg>
                            @endif
                            {{ $pembayaranTerakhir->metode_pembayaran }}
                        </p>
                    </div>
                    @if($kembalian > 0)
                    <div class="col-span-2 border-t border-white border-opacity-30 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold">KEMBALIAN</span>
                            <span class="text-2xl font-extrabold text-white">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($pembayaranTerakhir->catatan ?? false)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border-l-4 border-teal-500">
                <h6 class="font-bold text-gray-800 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Catatan Farmasi
                </h6>
                <p class="text-sm text-gray-700">{{ $pembayaranTerakhir->catatan }}</p>
            </div>
            @endif

            <div class="mt-6 text-center p-6 bg-teal-50 rounded-lg">
                <h5 class="font-bold text-lg text-teal-600 mb-3 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Terima Kasih Atas Pembelian Obat Anda
                </h5>
                <p class="text-sm text-gray-700">Simpan struk ini sebagai bukti pembayaran yang sah.</p>
                <p class="text-xs text-gray-500 mt-2">Dicetak pada: {{ now()->format('l, d F Y - H:i:s') }} WIB</p>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-center space-x-4 no-print">
            <button onclick="window.print()" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors shadow-md">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Struk
            </button>
            <a href="{{ route('kasir-apotek.dashboard') }}" class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>
</div>

{{-- Print-specific styles (for Tailwind/Blade environment) --}}
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    .no-print {
        display: none !important;
    }
    .bg-teal-600, .bg-teal-700, .bg-gray-50, .bg-green-500 {
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
}
</style>
@endsection