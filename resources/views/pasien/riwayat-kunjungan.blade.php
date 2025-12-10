@extends('layouts.dashboard')

@section('title', 'Riwayat Kunjungan')
@section('dashboard-title', 'Riwayat Kunjungan Saya')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Status</h2>

        <div class="flex gap-8 border-b border-gray-200">
            <button
                type="button"
                data-status=""
                class="status-tab pb-3 font-medium transition-colors text-[#f56e9d] border-b-2 border-[#f56e9d]"
            >
                Semua
            </button>
            <button
                type="button"
                data-status="menunggu"
                class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-[#f56e9d] border-b-2 border-transparent"
            >
                Menunggu
            </button>
            <button
                type="button"
                data-status="dipanggil"
                class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-[#f56e9d] border-b-2 border-transparent"
            >
                Dipanggil
            </button>
            <button
                type="button"
                data-status="selesai"
                class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-[#f56e9d] border-b-2 border-transparent"
            >
                Selesai
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div id="tableContainer" class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Daftar Kunjungan</h2>
        </div>

        <div id="tableContent">
        @if($pendaftarans->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Belum ada riwayat kunjungan</p>
            <a href="{{ route('pasien.pendaftaran-kunjungan') }}" class="mt-4 inline-block px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Daftar Kunjungan Sekarang
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kunjungan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pendaftarans as $pendaftaran)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pendaftaran->tanggal_daftar->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_kunjungan)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#fff5f8] text-[#d14a7a]">
                                {{ $pendaftaran->nomor_antrian }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}
                            @if($pendaftaran->jadwalDokter?->dokter?->spesialisasi)
                            <div class="text-xs text-gray-500">{{ $pendaftaran->jadwalDokter->dokter->spesialisasi }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($pendaftaran->jadwalDokter)
                            <div class="font-medium">{{ $pendaftaran->jadwalDokter->hari_praktik }}</div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($pendaftaran->jadwalDokter->waktu_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($pendaftaran->jadwalDokter->waktu_selesai)->format('H:i') }}
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $pendaftaran->keluhan_utama }}">
                                {{ $pendaftaran->keluhan_utama }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pendaftaran->status === 'menunggu')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                            @elseif($pendaftaran->status === 'dipanggil')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Dipanggil
                            </span>
                            @elseif($pendaftaran->status === 'diperiksa')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                Diperiksa
                            </span>
                            @elseif($pendaftaran->status === 'selesai')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Batal
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-700">
                    Menampilkan
                    <span class="font-medium">{{ $pendaftarans->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="font-medium">{{ $pendaftarans->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-medium">{{ $pendaftarans->total() }}</span>
                    data
                </p>
                @if($pendaftarans->hasPages())
                <div>
                    {{ $pendaftarans->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
        @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusTabs = document.querySelectorAll('.status-tab');
    let currentStatus = '';

    function fetchData(page = 1) {
        const params = new URLSearchParams({
            status: currentStatus,
            page: page
        });

        const url = '{{ route("pasien.riwayat-kunjungan") }}?' + params.toString();

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');

                const newContent = doc.getElementById('tableContent');
                const newPagination = doc.getElementById('paginationContainer');

                if (newContent) {
                    document.getElementById('tableContent').innerHTML = newContent.innerHTML;
                }

                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                    attachPaginationListeners();
                }
            }
        };

        xhr.send();
    }

    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('#paginationContainer a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                fetchData(page);
            });
        });
    }

    function setActiveTab(status) {
        statusTabs.forEach(tab => {
            if (tab.dataset.status === status) {
                tab.classList.remove('text-gray-500', 'border-transparent');
                tab.classList.add('text-[#f56e9d]', 'border-[#f56e9d]');
            } else {
                tab.classList.remove('text-[#f56e9d]', 'border-[#f56e9d]');
                tab.classList.add('text-gray-500', 'border-transparent');
            }
        });
    }

    statusTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            currentStatus = this.dataset.status;
            setActiveTab(currentStatus);
            fetchData(1);
        });
    });

    attachPaginationListeners();
});
</script>
@endsection
