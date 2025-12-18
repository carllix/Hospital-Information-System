@extends('layouts.dashboard')

@section('title', 'Daftar Resep | Ganesha Hospital')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Pencarian</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Cari Resep
                </label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                    placeholder="Nama Pasien / No. RM / Dokter"
                >
            </div>

            <!-- Tanggal -->
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Resep
                </label>
                <input
                    type="date"
                    name="tanggal"
                    id="tanggal"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
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
                    class="status-tab pb-3 font-medium transition-colors text-pink-500 border-b-2 border-pink-500 hover:cursor-pointer"
                >
                    Semua
                </button>
                <button
                    type="button"
                    data-status="menunggu"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-pink-500 hover:cursor-pointer border-b-2 border-transparent"
                >
                    Menunggu
                </button>
                <button
                    type="button"
                    data-status="diproses"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-pink-500 border-b-2 border-transparent hover:cursor-pointer"
                >
                    Diproses
                </button>
                <button
                    type="button"
                    data-status="selesai"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-pink-500 border-b-2 border-transparent hover:cursor-pointer"
                >
                    Selesai
                </button>
            </div>
        </div>

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Daftar Resep</h2>
        </div>

        <div id="tableContent">
        @if($resepList->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada data resep</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Resep</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apoteker</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($resepList as $resep)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $resep->resep_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $resep->tanggal_resep ? $resep->tanggal_resep->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $resep->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</div>
                            <div class="text-gray-500">{{ $resep->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $resep->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $resep->apoteker ? $resep->apoteker->nama_lengkap : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($resep->status == 'menunggu')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            @elseif($resep->status == 'diproses')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Diproses
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('farmasi.detail-resep', $resep->resep_id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-pink-500 text-white text-xs font-medium rounded hover:bg-pink-600 transition-colors">
                                Detail
                            </a>
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
                    <span class="font-medium">{{ $resepList->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="font-medium">{{ $resepList->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-medium">{{ $resepList->total() }}</span>
                    data
                </p>
                @if($resepList->hasPages())
                <div>
                    {{ $resepList->withQueryString()->links() }}
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
    const tanggalInput = document.getElementById('tanggal');
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
            tanggal: tanggalInput.value,
            status: currentStatus === '' ? 'semua' : currentStatus,
            page: page
        });

        const url = '{{ route("farmasi.daftar-resep") }}?' + params.toString();

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
                tab.classList.add('text-pink-500', 'border-pink-500');
            } else {
                tab.classList.remove('text-pink-500', 'border-pink-500');
                tab.classList.add('text-gray-500', 'border-transparent');
            }
        });
    }

    const debouncedFetch = debounce(() => fetchData(1), 500);

    searchInput.addEventListener('input', debouncedFetch);
    tanggalInput.addEventListener('change', debouncedFetch);

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

@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif
@endsection
