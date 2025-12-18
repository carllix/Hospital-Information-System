@extends('layouts.dashboard')

@section('title', 'Buat Tagihan')

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
            <h1 class="text-2xl font-bold text-gray-800">Buat Tagihan</h1>
            <p class="text-gray-600">Form pembuatan tagihan pembayaran</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Pasien & Pemeriksaan -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Data Pasien -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Informasi Pasien</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Pasien</p>
                        <p class="font-semibold text-gray-900">{{ $pemeriksaan->pendaftaran->pasien->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No. Rekam Medis</p>
                        <p class="font-semibold text-gray-900">{{ $pemeriksaan->pendaftaran->pasien->no_rekam_medis }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dokter</p>
                        <p class="font-semibold text-gray-900">{{ $pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pemeriksaan</p>
                        <p class="font-semibold text-gray-900">{{ $pemeriksaan->tanggal_pemeriksaan->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Diagnosa</p>
                        <p class="font-semibold text-gray-900">{{ $pemeriksaan->diagnosa }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tagihan -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('kasir.store-tagihan', $pemeriksaan->pemeriksaan_id) }}" id="formTagihan" class="space-y-8">
                @csrf

                <!-- Pilih Layanan -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Pilih Layanan</h3>
                        <p class="text-sm text-gray-600 mt-1">Centang layanan yang diberikan kepada pasien</p>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($layananList as $layanan)
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg hover:border-pink-500 hover:bg-pink-50 cursor-pointer transition-all">
                            <input type="checkbox" name="layanan[]" value="{{ $layanan->layanan_id }}"
                                data-harga="{{ $layanan->harga }}"
                                data-nama="{{ $layanan->nama_layanan }}"
                                data-kategori="{{ $layanan->kategori }}"
                                class="layanan-checkbox mt-1 w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500"
                                {{ $layanan->kategori === 'konsultasi' ? 'checked' : '' }}>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $layanan->nama_layanan }}</p>
                                        <p class="text-sm text-gray-500 capitalize">{{ $layanan->kategori }}</p>
                                    </div>
                                    <p class="font-bold text-pink-600">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </label>
                        @empty
                        <p class="text-gray-500 text-center py-4">Tidak ada layanan tersedia</p>
                        @endforelse
                    </div>
                </div>

                <!-- Item Otomatis (Obat & Lab) -->
                @if(count($itemObat) > 0 || count($itemLab) > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Item Otomatis Termasuk</h3>
                        <p class="text-sm text-gray-600 mt-1">Item berikut otomatis ditambahkan ke tagihan</p>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Obat dari Resep -->
                        @foreach($itemObat as $obat)
                        <div class="flex items-center justify-between p-4 bg-pink-50 border border-pink-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 bg-pink-100 text-pink-700 text-xs rounded-full font-semibold">Obat</span>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $obat['nama'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $obat['jumlah'] }} x Rp {{ number_format($obat['harga_satuan'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <p class="font-bold text-pink-600">Rp {{ number_format($obat['subtotal'], 0, ',', '.') }}</p>
                        </div>
                        @endforeach

                        <!-- Lab -->
                        @foreach($itemLab as $lab)
                        <div class="flex items-center justify-between p-4 bg-pink-50 border border-pink-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 bg-pink-100 text-pink-700 text-xs rounded-full font-semibold">Lab</span>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $lab['nama'] }}</p>
                                </div>
                            </div>
                            <p class="font-bold text-pink-600">Rp {{ number_format($lab['harga'], 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Total Tagihan -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                            <span class="text-base font-semibold text-gray-700">Total Layanan:</span>
                            <span class="text-lg font-bold text-pink-600" id="totalLayanan">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                            <span class="text-base font-semibold text-gray-700">Total Obat & Lab:</span>
                            <span class="text-lg font-bold text-pink-600">Rp {{ number_format($totalObatLab, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xl font-bold text-gray-900">TOTAL TAGIHAN:</span>
                            <span class="text-2xl font-bold text-pink-600" id="grandTotal">Rp {{ number_format($totalObatLab, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-pink-500 text-white font-semibold py-3 rounded-lg hover:bg-pink-600 transition-colors shadow-md">
                        Buat Tagihan
                    </button>
                    <a href="{{ route('kasir.dashboard') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const totalObatLab = {{ $totalObatLab }};

    function updateTotal() {
        let totalLayanan = 0;
        document.querySelectorAll('.layanan-checkbox:checked').forEach(checkbox => {
            totalLayanan += parseFloat(checkbox.dataset.harga);
        });

        const grandTotal = totalLayanan + totalObatLab;

        document.getElementById('totalLayanan').textContent = 'Rp ' + totalLayanan.toLocaleString('id-ID');
        document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    document.querySelectorAll('.layanan-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateTotal);
    });

    // Initial calculation
    updateTotal();
</script>

@if(session('error'))
<x-toast type="error" :message="session('error')" />
@endif
@endsection
