@extends('layouts.dashboard')

@section('title', 'Manajemen Staf | Admin Ganesha Hospital')
@section('dashboard-title', 'Manajemen Staf')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manajemen Staf</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data staf rumah sakit</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm text-gray-600">Total Staf</p>
                    <p class="text-3xl font-bold text-[#f56e9d]">{{ $stafs->total() }}</p>
                </div>
                <a href="{{ route('admin.staf.create') }}" class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Staf
                </a>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari Staf
                    </label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama, NIP RS, atau NIK..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" />
                </div>

                <div>
                    <label for="bagian" class="block text-sm font-medium text-gray-700 mb-2">
                        Bagian
                    </label>
                    <select
                        name="bagian"
                        id="bagian"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                        <option value="">Semua Bagian</option>
                        <option value="pendaftaran" {{ request('bagian') === 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                        <option value="farmasi" {{ request('bagian') === 'farmasi' ? 'selected' : '' }}>Farmasi</option>
                        <option value="laboratorium" {{ request('bagian') === 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                        <option value="kasir" {{ request('bagian') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="tableContainer" class="bg-white rounded-lg shadow-md overflow-hidden">
        <div id="tableContent">
            @if($stafs->isEmpty())
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="mt-4 text-gray-600">Belum ada data staf</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIP RS
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Staf
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bagian
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Telepon
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stafs as $staf)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-[#f56e9d]">{{ $staf->nip_rs }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $staf->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $staf->jenis_kelamin }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                $badgeColor = match($staf->bagian) {
                                'pendaftaran' => 'bg-blue-100 text-blue-800',
                                'farmasi' => 'bg-green-100 text-green-800',
                                'laboratorium' => 'bg-purple-100 text-purple-800',
                                'kasir' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-gray-100 text-gray-800',
                                };
                                @endphp
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $badgeColor }}">
                                    {{ ucfirst($staf->bagian) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $staf->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $staf->no_telepon }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.staf.show', $staf->staf_id) }}"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.staf.edit', $staf->staf_id) }}"
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button
                                        type="button"
                                        onclick="showDeleteModal({{ $staf->staf_id }}, '{{ $staf->nama_lengkap }}')"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <form id="deleteForm-{{ $staf->staf_id }}" action="{{ route('admin.staf.destroy', $staf->staf_id) }}" method="POST" class="hidden">
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

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium">{{ $stafs->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-medium">{{ $stafs->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium">{{ $stafs->total() }}</span>
                        data
                    </p>
                    @if($stafs->hasPages())
                    <div>
                        {{ $stafs->links() }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="deleteModalBackdrop" class="fixed inset-0 bg-black opacity-0 transition-opacity duration-200" onclick="closeDeleteModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0 relative z-10" id="deleteModalContent">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Yakin ingin menghapus staf <strong id="deleteItemName"></strong>? Tindakan ini tidak dapat dibatalkan.
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
                class="px-3 py-2 text-xs bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium shadow-sm">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
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

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const bagianSelect = document.getElementById('bagian');
        let debounceTimer = null;

        function debounce(func, delay) {
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(func, delay);
            };
        }

        function fetchData() {
            const params = new URLSearchParams({
                search: searchInput.value,
                bagian: bagianSelect.value
            });

            const url = '{{ route("admin.staf.index") }}?' + params.toString();

            const xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');

                    const newContent = doc.getElementById('tableContent');
                    const newTotal = doc.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]');

                    if (newContent) {
                        document.getElementById('tableContent').innerHTML = newContent.innerHTML;
                    }

                    if (newTotal) {
                        document.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]').textContent = newTotal.textContent;
                    }

                    window.history.pushState({}, '', url);
                }
            };

            xhr.send();
        }

        const debouncedFetch = debounce(() => fetchData(), 500);

        searchInput.addEventListener('input', debouncedFetch);
        bagianSelect.addEventListener('change', debouncedFetch);
    });
</script>
@endsection