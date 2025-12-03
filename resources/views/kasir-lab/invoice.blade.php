@extends('layouts.dashboard')

@section('title', 'Struk Pembayaran Lab')

@section('content')
<div class="container-fluid">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden print-area">
        <div class="bg-gray-50 p-6 text-center border-b-4 border-purple-600">
            <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-3 text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <h4 class="font-bold text-xl text-gray-900 mb-1">GANESHA HOSPITAL</h4>
            <p class="text-sm text-gray-600 mb-1">Jl. Kesehatan No. 123, Bandung, Jawa Barat</p>
            <p class="text-sm text-gray-600">Telp: (022) 1234567 | Email: info@ganesha.co.id</p>
        </div>

        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">
                        <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        INVOICE LABORATORIUM #{{ $tagihan->tagihan_id }}
                    </h3>
                    <p class="opacity-90 text-sm mt-1">Pembayaran Lab Berhasil Diproses</p>
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
                    <h6 class="font-bold text-purple-600 mb-3 flex items-center">
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
                    <h6 class="font-bold text-purple-600 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Informasi Transaksi Lab
                    </h6>
                    <p class="text-sm"><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($pembayaranTerakhir->tanggal_bayar)->format('d/m/Y H:i:s') }}</p>
                    <p class="text-sm"><strong>Jenis Tagihan:</strong> 
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            LABORATORIUM
                        </span>
                    </p>
                    <p class="text-sm"><strong>Kasir Lab:</strong> {{ auth()->user()->name ?? 'System' }}</p>
                </div>
            </div>

            <h6 class="font-bold text-purple-600 mb-3 border-t pt-4 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Detail Pemeriksaan Laboratorium
            </h6>
            <div class="overflow-x-auto border rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-purple-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Jenis Pemeriksaan Lab</th>
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
                                    <br><small class="text-gray-500">{{ $detail->catatan }}</small>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">{{ $detail->kuantitas }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-700">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-sm text-purple-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-50">
                            <th colspan="3" class="px-4 py-3 text-right text-base font-bold text-gray-900">TOTAL TAGIHAN LAB</th>
                            <th class="px-4 py-3 text-right text-lg font-extrabold text-purple-600">
                                Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 bg-green-500 text-white p-5 rounded-lg shadow-lg">
                <h6 class="font-bold mb-3 flex items-center text-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Ringkasan Pembayaran Lab
                </h6>
                <div class="grid grid-cols-2 gap-4 text-sm font-medium">
                    <div>
                        <p class="opacity-90">Total Tagihan Lab:</p>
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
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18M13 16l4-4m0 0l-4-4m4 4H3"/></svg>
                            @endif
                            {{ $pembayaranTerakhir->metode_pembayaran }}
                        </p>
                    </div>
                    <div class="col-span-2 border-t border-white border-opacity-30 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold">KEMBALIAN</span>
                            <span class="text-2xl font-extrabold text-white">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($pembayaranTerakhir->catatan ?? false)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border-l-4 border-purple-500">
                <h6 class="font-bold text-gray-800 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Catatan Kasir Lab
                </h6>
                <p class="text-sm text-gray-700">{{ $pembayaranTerakhir->catatan }}</p>
            </div>
            @endif

            <div class="mt-6 text-center p-6 bg-purple-50 rounded-lg">
                <h5 class="font-bold text-lg text-purple-600 mb-3 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Terima Kasih Atas Kunjungan Anda
                </h5>
                <p class="text-sm text-gray-700">Hasil pemeriksaan laboratorium dapat diambil sesuai jadwal yang telah ditentukan.</p>
                <p class="text-xs text-gray-500 mt-2">Dicetak pada: {{ now()->format('l, d F Y - H:i:s') }} WIB</p>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-center space-x-4 no-print">
            <button onclick="window.print()" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors shadow-md">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Struk Lab
            </button>
            <a href="{{ route('kasir-lab.dashboard') }}" class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>
</div>

{{-- Success Message with Kembalian (using component for consistency) --}}
@if(session('success'))
    @php
        $message = session('success');
        if ($kembalian > 0) {
            $message .= " Kembalian: Rp " . number_format($kembalian, 0, ',', '.');
        }
    @endphp
    <x-toast type="success" :message="$message" />
@endif

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
    .bg-purple-600, .bg-purple-700, .bg-gray-50, .bg-green-500 {
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
}
</style>
@endsection