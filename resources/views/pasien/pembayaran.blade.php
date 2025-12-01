@extends('layouts.dashboard')

@section('title', 'Pembayaran - Ganesha Hospital')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="max-w-7xl space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Riwayat Tagihan & Pembayaran</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola pembayaran dan tagihan Anda</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Tagihan Belum Bayar</p>
                <p class="text-3xl font-bold text-red-500">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">
                    Status Pembayaran
                </label>
                <select
                    id="status_filter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div>
                <label for="jenis_filter" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Tagihan
                </label>
                <select
                    id="jenis_filter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                >
                    <option value="">Semua Jenis</option>
                    <option value="konsultasi" {{ request('jenis') === 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                    <option value="obat" {{ request('jenis') === 'obat' ? 'selected' : '' }}>Obat</option>
                    <option value="lab" {{ request('jenis') === 'lab' ? 'selected' : '' }}>Laboratorium</option>
                    <option value="tindakan" {{ request('jenis') === 'tindakan' ? 'selected' : '' }}>Tindakan</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tagihan List -->
    <div id="tagihanContainer">
        <div id="tagihanContent">
        @if($tagihans->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada tagihan</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($tagihans as $tagihan)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">
                                    Tagihan #{{ $tagihan->tagihan_id }}
                                </h3>
                                @if($tagihan->status === 'lunas')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Lunas
                                </span>
                                @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Belum Bayar
                                </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>Tanggal: {{ $tagihan->created_at->format('d/m/Y H:i') }}</p>
                                <p class="capitalize">Jenis: {{ ucfirst($tagihan->jenis_tagihan) }}</p>
                                @if($tagihan->pendaftaran)
                                <p>No. Pendaftaran: {{ $tagihan->pendaftaran->nomor_antrian }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Total Tagihan</p>
                            <p class="text-2xl font-bold text-[#f56e9d]">
                                Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Detail Items -->
                    @if($tagihan->detailTagihan->isNotEmpty())
                    <div class="mt-4 border-t pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Detail Tagihan:</h4>
                        <div class="space-y-2">
                            @foreach($tagihan->detailTagihan as $detail)
                            <div class="flex justify-between items-center text-sm bg-gray-50 p-3 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $detail->nama_item }}</p>
                                    <p class="text-xs text-gray-600">
                                        {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="font-semibold text-gray-900">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Payment Info if Lunas -->
                    @if($tagihan->status === 'lunas' && $tagihan->pembayaran)
                    <div class="mt-4 border-t pt-4 bg-green-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Informasi Pembayaran:</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-600">Tanggal Bayar:</p>
                                <p class="font-medium text-gray-900">{{ $tagihan->pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Metode Pembayaran:</p>
                                <p class="font-medium text-gray-900 capitalize">{{ ucfirst($tagihan->pembayaran->metode_pembayaran) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">No. Kwitansi:</p>
                                <p class="font-medium text-gray-900">{{ $tagihan->pembayaran->no_kwitansi }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Jumlah Bayar:</p>
                                <p class="font-medium text-green-600">Rp {{ number_format($tagihan->pembayaran->jumlah_bayar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tagihans->links() }}
        </div>
        @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status_filter');
    const jenisFilter = document.getElementById('jenis_filter');
    let debounceTimer = null;

    function debounce(func, delay) {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        };
    }

    function fetchData() {
        const params = new URLSearchParams({
            status: statusFilter.value,
            jenis: jenisFilter.value
        });

        const url = '{{ route("pasien.pembayaran") }}?' + params.toString();

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');

                const newContent = doc.getElementById('tagihanContent');
                const newTotal = doc.querySelector('.text-3xl.font-bold.text-red-500');

                if (newContent) {
                    document.getElementById('tagihanContent').innerHTML = newContent.innerHTML;
                }

                if (newTotal) {
                    document.querySelector('.text-3xl.font-bold.text-red-500').textContent = newTotal.textContent;
                }

                // Update URL without reload
                window.history.pushState({}, '', url);
            }
        };

        xhr.send();
    }

    const debouncedFetch = debounce(() => fetchData(), 500);

    statusFilter.addEventListener('change', debouncedFetch);
    jenisFilter.addEventListener('change', debouncedFetch);
});
</script>
@endsection
