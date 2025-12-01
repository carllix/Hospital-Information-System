@extends('layouts.dashboard')

@section('title', 'Rekam Medis - Pasien')
@section('dashboard-title', 'Rekam Medis')

@section('content')
<div class="w-full min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Rekam Medis</h2>

        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Diagnosa atau dokter..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                </div>

                <div>
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                </div>

                <div>
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                        <option value="">Semua Status</option>
                        <option value="selesai_penanganan" {{ request('status') == 'selesai_penanganan' ? 'selected' : '' }}>Selesai</option>
                        <option value="dirujuk" {{ request('status') == 'dirujuk' ? 'selected' : '' }}>Dirujuk</option>
                        <option value="perlu_resep" {{ request('status') == 'perlu_resep' ? 'selected' : '' }}>Perlu Resep</option>
                        <option value="perlu_lab" {{ request('status') == 'perlu_lab' ? 'selected' : '' }}>Perlu Lab</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="loadingIndicator" class="hidden mb-4">
            <div class="flex items-center justify-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#f56e9d]"></div>
                <span class="ml-3 text-gray-600">Memuat data...</span>
            </div>
        </div>

        <div id="tableContainer" data-url="{{ route('pasien.rekam-medis') }}">
            @include('pasien.components.rekam-medis-table', ['pemeriksaan' => $pemeriksaan])
        </div>
    </div>
</div>

<div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
        <div id="modalContent"></div>
    </div>
</div>

<script>
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const searchInput = document.getElementById('search');
    const tanggalDariInput = document.getElementById('tanggal_dari');
    const tanggalSampaiInput = document.getElementById('tanggal_sampai');
    const statusSelect = document.getElementById('status');
    const tableContainer = document.getElementById('tableContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');

    const rekamMedisUrl = document.getElementById('tableContainer').dataset.url;

    function fetchData() {
        loadingIndicator.classList.remove('hidden');
        tableContainer.style.opacity = '0.5';

        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (tanggalDariInput.value) params.append('tanggal_dari', tanggalDariInput.value);
        if (tanggalSampaiInput.value) params.append('tanggal_sampai', tanggalSampaiInput.value);
        if (statusSelect.value) params.append('status', statusSelect.value);

        const xhr = new XMLHttpRequest();
        const url = `${rekamMedisUrl}?${params.toString()}`;

        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'text/html');

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                tableContainer.innerHTML = xhr.responseText;

                loadingIndicator.classList.add('hidden');
                tableContainer.style.opacity = '1';

                const newUrl = `${rekamMedisUrl}?${params.toString()}`;
                window.history.pushState({}, '', newUrl);

                attachPaginationHandlers();
            } else {
                console.error('Request failed with status:', xhr.status);
                loadingIndicator.classList.add('hidden');
                tableContainer.style.opacity = '1';
            }
        };

        xhr.onerror = function() {
            console.error('Request failed');
            loadingIndicator.classList.add('hidden');
            tableContainer.style.opacity = '1';
        };

        xhr.send();
    }

    function attachPaginationHandlers() {
        const paginationLinks = document.querySelectorAll('#paginationContainer a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href;

                loadingIndicator.classList.remove('hidden');
                tableContainer.style.opacity = '0.5';

                const xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'text/html');

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        tableContainer.innerHTML = xhr.responseText;
                        loadingIndicator.classList.add('hidden');
                        tableContainer.style.opacity = '1';
                        window.history.pushState({}, '', url);

                        tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });

                        attachPaginationHandlers();
                    } else {
                        console.error('Request failed with status:', xhr.status);
                        loadingIndicator.classList.add('hidden');
                        tableContainer.style.opacity = '1';
                    }
                };

                xhr.onerror = function() {
                    console.error('Request failed');
                    loadingIndicator.classList.add('hidden');
                    tableContainer.style.opacity = '1';
                };

                xhr.send();
            });
        });
    }

    const debouncedFetch = debounce(fetchData, 500);

    searchInput.addEventListener('input', debouncedFetch);
    tanggalDariInput.addEventListener('change', debouncedFetch);
    tanggalSampaiInput.addEventListener('change', debouncedFetch);

    statusSelect.addEventListener('change', fetchData);

    document.addEventListener('DOMContentLoaded', () => {
        attachPaginationHandlers();
    });

    window.openDetailModal = function(pemeriksaanId) {
        alert('Detail untuk pemeriksaan ID: ' + pemeriksaanId);
    };
</script>
@endsection
