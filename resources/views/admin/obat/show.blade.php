@extends('layouts.dashboard')

@section('title', 'Detail Obat')
@section('dashboard-title', 'Detail Obat')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Obat</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap obat</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.obat.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Kembali
                </a>
                <a href="{{ route('admin.obat.edit', $obat->obat_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                    Edit
                </a>
                <form action="{{ route('admin.obat.destroy', $obat->obat_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kode Obat</label>
                    <p class="mt-1 text-lg font-semibold text-[#f56e9d]">{{ $obat->kode_obat }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Nama Obat</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $obat->nama_obat }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Kategori</label>
                    <p class="mt-1">
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($obat->kategori) }}
                        </span>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Satuan</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $obat->satuan }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Stok Tersedia</label>
                    <div class="mt-1">
                        @if($obat->stok <= 0)
                        <span class="text-2xl font-bold text-red-600">{{ $obat->stok }}</span>
                        <span class="ml-2 px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Stok Habis
                        </span>
                        @elseif($obat->stok <= $obat->stok_minimum)
                        <span class="text-2xl font-bold text-yellow-600">{{ $obat->stok }}</span>
                        <span class="ml-2 px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Stok Menipis
                        </span>
                        @else
                        <span class="text-2xl font-bold text-green-600">{{ $obat->stok }}</span>
                        <span class="ml-2 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Stok Aman
                        </span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Stok Minimum</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $obat->stok_minimum }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Harga</label>
                    <p class="mt-1 text-2xl font-bold text-[#f56e9d]">Rp {{ number_format($obat->harga, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($obat->deskripsi)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                <p class="mt-1 text-gray-900">{{ $obat->deskripsi }}</p>
            </div>
            @endif

            <div class="md:col-span-2 pt-4 border-t">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Ditambahkan:</span>
                        <span class="ml-2">{{ $obat->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Terakhir Diupdate:</span>
                        <span class="ml-2">{{ $obat->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
