@extends('layouts.dashboard')

@section('title', 'Stok Obat | Ganesha Hospital')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Pencarian</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Cari Obat
                </label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ $search }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                    placeholder="Nama Obat / Kode Obat">
            </div>

            <!-- Kategori -->
            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori
                </label>
                <select
                    name="kategori"
                    id="kategori"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    <option value="tablet" {{ $kategori == 'tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="kapsul" {{ $kategori == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                    <option value="sirup" {{ $kategori == 'sirup' ? 'selected' : '' }}>Sirup</option>
                    <option value="salep" {{ $kategori == 'salep' ? 'selected' : '' }}>Salep</option>
                    <option value="injeksi" {{ $kategori == 'injeksi' ? 'selected' : '' }}>Injeksi</option>
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
                    class="status-tab pb-3 font-medium transition-colors text-pink-500 border-b-2 border-pink-500 hover:cursor-pointer">
                    Semua
                </button>
                <button
                    type="button"
                    data-status="habis"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-pink-500 hover:cursor-pointer border-b-2 border-transparent">
                    Stok Habis
                </button>
                <button
                    type="button"
                    data-status="menipis"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-pink-500 border-b-2 border-transparent hover:cursor-pointer">
                    Stok Menipis
                </button>
                <button
                    type="button"
                    data-status="aman"
                    class="status-tab pb-3 font-medium transition-colors text-gray-500 hover:text-pink-500 border-b-2 border-transparent hover:cursor-pointer">
                    Stok Aman
                </button>
            </div>
        </div>

        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Manajemen Stok Obat</h2>
            <a href="{{ route('farmasi.tambah-obat') }}"
                class="inline-flex items-center px-4 py-2 bg-pink-500 text-white text-sm font-medium rounded-lg hover:bg-pink-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Obat
            </a>
        </div>

        <div id="tableContent">
            @if($obatList->isEmpty())
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="mt-4 text-gray-600">Tidak ada data obat</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Obat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Obat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($obatList as $obat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $obat->kode_obat }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $obat->nama_obat }}</div>
                                @if($obat->deskripsi)
                                <div class="text-gray-500 text-xs truncate max-w-xs">{{ $obat->deskripsi }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 capitalize">
                                    {{ $obat->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($obat->stok == 0)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Habis
                                </span>
                                @elseif($obat->stok < 10)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                    </svg>
                                    {{ $obat->stok }} {{ $obat->satuan }}
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $obat->stok }} {{ $obat->satuan }}
                                    </span>
                                    @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openStokModal({{ $obat->obat_id }}, {{ json_encode($obat->nama_obat) }}, {{ $obat->stok }})"
                                        class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition-colors"
                                        title="Update Stok">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>

                                    <a href="{{ route('farmasi.edit-obat', $obat->obat_id) }}"
                                        class="inline-flex items-center px-2 py-1 bg-amber-500 text-white text-xs font-medium rounded hover:bg-amber-600 transition-colors"
                                        title="Edit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    <button type="button"
                                        onclick="showDeleteModal({{ $obat->obat_id }}, {{ json_encode($obat->nama_obat) }})"
                                        class="inline-flex items-center px-2 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition-colors"
                                        title="Hapus">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="deleteForm-{{ $obat->obat_id }}" action="{{ route('farmasi.delete-obat', $obat->obat_id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
                        <span class="font-medium">{{ $obatList->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-medium">{{ $obatList->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium">{{ $obatList->total() }}</span>
                        data
                    </p>
                    @if($obatList->hasPages())
                    <div>
                        {{ $obatList->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Update Stok -->
<div id="stokModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeStokModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:w-full sm:max-w-lg">
            <div class="bg-white px-6 pt-5 pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Update Stok Obat</h3>
                    <button type="button" onclick="closeStokModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="stokForm" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4 bg-pink-50 rounded-lg p-4 border border-pink-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-pink-600 font-medium">Nama Obat</p>
                                <p id="namaObat" class="text-sm font-bold text-gray-800 mt-1"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-pink-600 font-medium">Stok Saat Ini</p>
                                <p id="stokSaatIni" class="text-sm font-bold text-gray-800 mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tindakan</label>
                            <select name="tipe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                                <option value="tambah">Tambah Stok</option>
                                <option value="kurang">Kurangi Stok</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <input type="number" name="jumlah" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500" placeholder="0">
                        </div>
                    </div>

                    <div class="mt-5 flex gap-3">
                        <button type="button" onclick="closeStokModal()" class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-pink-500 text-white font-medium rounded-lg hover:bg-pink-600 transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const kategoriSelect = document.getElementById('kategori');
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
                kategori: kategoriSelect.value,
                status_stok: currentStatus,
                page: page
            });

            const url = '{{ route("farmasi.stok-obat") }}?' + params.toString();

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
        kategoriSelect.addEventListener('change', debouncedFetch);

        statusTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                currentStatus = this.dataset.status;
                setActiveTab(currentStatus);
                fetchData(1);
            });
        });

        attachPaginationListeners();
    });

    // Modal Update Stok
    function openStokModal(obatId, namaObat, stokSaatIni) {
        const modal = document.getElementById('stokModal');
        modal.classList.remove('hidden');
        document.getElementById('namaObat').textContent = namaObat;
        document.getElementById('stokSaatIni').textContent = stokSaatIni;
        document.getElementById('stokForm').action = `/farmasi/obat/${obatId}/stok`;
    }

    function closeStokModal() {
        const modal = document.getElementById('stokModal');
        modal.classList.add('hidden');
        document.getElementById('stokForm').reset();
    }

    // Modal Delete Konfirmasi
    let deleteItemId = null;

    function showDeleteModal(id, name) {
        deleteItemId = id;
        document.getElementById('deleteItemName').textContent = name;

        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const modalBackdrop = document.getElementById('deleteModalBackdrop');

        modal.classList.remove('hidden');

        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
            modalBackdrop.classList.remove('opacity-0');
            modalBackdrop.classList.add('opacity-20');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const modalBackdrop = document.getElementById('deleteModalBackdrop');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        modalBackdrop.classList.remove('opacity-20');
        modalBackdrop.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            deleteItemId = null;
        }, 200);
    }

    function confirmDelete() {
        if (deleteItemId) {
            document.getElementById('deleteForm-' + deleteItemId).submit();
        }
    }
</script>

<!-- Modal Konfirmasi Delete -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="deleteModalBackdrop" class="fixed inset-0 bg-black opacity-0 transition-opacity duration-200" onclick="closeDeleteModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0 relative z-10" id="deleteModalContent">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus Obat</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Yakin ingin menghapus obat <strong id="deleteItemName"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>
        </div>
        <div class="p-6 flex gap-3 justify-end">
            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-3 py-2 text-xs bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Batal
            </button>
            <button
                type="button"
                onclick="confirmDelete()"
                class="px-3 py-2 text-xs bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium shadow-sm">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif
@endsection