@extends('layouts.dashboard')

@section('title', 'Riwayat Pendaftaran | Pendaftaran Ganesha Hospital')
@section('dashboard-title', 'Riwayat Pendaftaran')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Pencarian</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Cari Pasien
                </label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                    placeholder="Nama / NIK / No. RM"
                >
            </div>

            <!-- Tanggal Dari -->
            <div>
                <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Dari
                </label>
                <input
                    type="date"
                    name="tanggal_dari"
                    id="tanggal_dari"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                >
            </div>

            <!-- Tanggal Sampai -->
            <div>
                <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Sampai
                </label>
                <input
                    type="date"
                    name="tanggal_sampai"
                    id="tanggal_sampai"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                >
            </div>

            <!-- Dokter -->
            <div>
                <label for="dokter_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Dokter
                </label>
                <select
                    name="dokter_id"
                    id="dokter_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
                >
                    <option value="">Semua Dokter</option>
                    @foreach($dokters as $dokter)
                    <option value="{{ $dokter->dokter_id }}">
                        {{ $dokter->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div id="tableContainer" class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Status Tabs -->
        <div class="px-6 pt-6 pb-4">
            <div class="flex gap-8 border-b border-gray-200">
                <button
                    type="button"
                    data-status=""
                    class="status-tab pb-3 font-medium transition-colors text-[#f56e9d] border-b-2 border-[#f56e9d] hover:cursor-pointer"
                >
                    Semua
                </button>
                <button
                    type="button"
                    data-status="menunggu"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-[#f56e9d] hover:cursor-pointer border-b-2 border-transparent"
                >
                    Menunggu
                </button>
                <button
                    type="button"
                    data-status="dipanggil"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-[#f56e9d] border-b-2 border-transparent hover:cursor-pointer"
                >
                    Dipanggil
                </button>
                <button
                    type="button"
                    data-status="selesai"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-[#f56e9d] border-b-2 border-transparent hover:cursor-pointer"
                >
                    Selesai
                </button>
            </div>
        </div>

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Riwayat Pendaftaran</h2>
        </div>

        <div id="tableContent">
        @if($pendaftarans->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada data pendaftaran</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kunjungan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
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
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $pendaftaran->pasien->nama_lengkap }}</div>
                            <div class="text-gray-500">{{ $pendaftaran->pasien->no_rekam_medis }}</div>
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
                            @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $pendaftaran->stafPendaftaran->nama_lengkap ?? '-' }}
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
    const searchInput = document.getElementById('search');
    const tanggalDari = document.getElementById('tanggal_dari');
    const tanggalSampai = document.getElementById('tanggal_sampai');
    const dokterId = document.getElementById('dokter_id');
    const statusTabs = document.querySelectorAll('.status-tab');

    let debounceTimer = null;
    let currentStatus = '';

    function debounce(func, delay) {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        };
    }

    function fetchData(page = 1) {
        const params = new URLSearchParams({
            search: searchInput.value,
            tanggal_dari: tanggalDari.value,
            tanggal_sampai: tanggalSampai.value,
            dokter_id: dokterId.value,
            status: currentStatus,
            page: page
        });

        const url = '{{ route("pendaftaran.riwayat") }}?' + params.toString();

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');

                const newContent = doc.getElementById('tableContent');
                const newPagination = doc.getElementById('paginationContainer');
                const newTotal = doc.getElementById('totalCount');

                if (newContent) {
                    document.getElementById('tableContent').innerHTML = newContent.innerHTML;
                }

                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                    attachPaginationListeners();
                }

                if (newTotal) {
                    document.getElementById('totalCount').textContent = newTotal.textContent;
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

    const debouncedFetch = debounce(() => fetchData(1), 500);

    searchInput.addEventListener('input', debouncedFetch);
    tanggalDari.addEventListener('change', debouncedFetch);
    tanggalSampai.addEventListener('change', debouncedFetch);
    dokterId.addEventListener('change', debouncedFetch);

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
