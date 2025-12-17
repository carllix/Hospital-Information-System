@extends('layouts.dashboard')

@section('title', 'Riwayat Pemeriksaan | Ganesha Hospital')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Pencarian</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    placeholder="Nama / No. RM"
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
                    data-status="dalam_pemeriksaan"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-[#f56e9d] hover:cursor-pointer border-b-2 border-transparent"
                >
                    Dalam Pemeriksaan
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
            <h2 class="text-lg font-bold text-gray-800">Riwayat Pemeriksaan</h2>
        </div>

        <div id="tableContent">
        @if($riwayatPemeriksaan->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada data pemeriksaan</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Periksa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ICD-10</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($riwayatPemeriksaan as $pemeriksaan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</div>
                            <div class="text-gray-500">{{ $pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $pemeriksaan->diagnosa }}">
                                {{ $pemeriksaan->diagnosa }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pemeriksaan->icd10_code ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pemeriksaan->status === 'selesai')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                            @elseif($pemeriksaan->status === 'dalam_pemeriksaan')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Dalam Pemeriksaan
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucwords(str_replace('_', ' ', $pemeriksaan->status ?? '-')) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('dokter.detail-pemeriksaan', $pemeriksaan->pemeriksaan_id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-[#f56e9d] text-white text-xs font-medium rounded hover:bg-[#d14a7a] transition-colors">
                                    Detail
                                </a>
                                <a href="{{ route('dokter.monitoring-pasien', $pemeriksaan->pendaftaran->pasien->pasien_id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-green-500 text-white text-xs font-medium rounded hover:bg-emerald-600 transition-colors">
                                    Monitoring
                                </a>
                            </div>
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
                    <span class="font-medium">{{ $riwayatPemeriksaan->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="font-medium">{{ $riwayatPemeriksaan->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-medium">{{ $riwayatPemeriksaan->total() }}</span>
                    data
                </p>
                @if($riwayatPemeriksaan->hasPages())
                <div>
                    {{ $riwayatPemeriksaan->withQueryString()->links() }}
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
            status: currentStatus,
            page: page
        });

        const url = '{{ route("dokter.riwayat") }}?' + params.toString();

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

    const debouncedFetch = debounce(() => fetchData(1), 500);

    searchInput.addEventListener('input', debouncedFetch);
    tanggalDari.addEventListener('change', debouncedFetch);
    tanggalSampai.addEventListener('change', debouncedFetch);

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