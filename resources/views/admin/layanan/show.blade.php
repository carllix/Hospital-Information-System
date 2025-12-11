@extends('layouts.dashboard')

@section('title', 'Detail Layanan')
@section('dashboard-title', 'Detail Layanan')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Layanan</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap layanan</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.layanan.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Kembali
                </a>
                <a href="{{ route('admin.layanan.edit', $layanan->layanan_id) }}" class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">
                    Edit
                </a>
                <button
                    type="button"
                    onclick="showDeleteModal()"
                    class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">
                    Hapus
                </button>
                <form id="deleteForm" action="{{ route('admin.layanan.destroy', $layanan->layanan_id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kode Layanan</label>
                    <p class="mt-1 text-lg font-semibold text-[#f56e9d]">{{ $layanan->kode_layanan }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Nama Layanan</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $layanan->nama_layanan }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kategori</label>
                    <p class="mt-1">
                        @if($layanan->kategori === 'konsultasi')
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-green-100 text-green-800">
                            Konsultasi
                        </span>
                        @else
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                            Tindakan
                        </span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Harga</label>
                    <p class="mt-1 text-2xl font-bold text-[#f56e9d]">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div id="deleteModal" class="ml-68 hidden fixed inset-0 z-50 flex items-center justify-center p-4 lg:ml-64">
    <div id="deleteModalBackdrop" class="absolute inset-0 bg-black opacity-0 transition-opacity duration-200" onclick="closeDeleteModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0 relative z-10" id="deleteModalContent">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Yakin ingin menghapus layanan <strong>{{ $layanan->nama_layanan }}</strong>? Tindakan ini tidak dapat dibatalkan.
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
    function showDeleteModal() {
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
        }, 200);
    }

    function confirmDelete() {
        document.getElementById('deleteForm').submit();
    }
</script>
@endsection