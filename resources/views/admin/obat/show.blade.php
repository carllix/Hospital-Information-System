@extends('layouts.dashboard')

@section('title', 'Detail Obat | Admin Ganesha Hospital')
@section('dashboard-title', 'Detail Obat')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <div class="mb-6">
        <a href="{{ route('admin.obat.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-medium">Kembali ke Daftar Obat</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Obat</h2>
            </div>
            <a href="{{ route('admin.obat.edit', $obat->obat_id) }}" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Edit
            </a>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kode Obat</label>
                    <p class="text-gray-900">{{ $obat->kode_obat }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Obat</label>
                    <p class="text-gray-900">{{ $obat->nama_obat }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <p class="text-gray-900">
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($obat->kategori) }}
                        </span>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Satuan</label>
                    <p class="text-gray-900">{{ $obat->satuan }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Stok Tersedia</label>
                    <p class="text-gray-900">{{ $obat->stok }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Stok Minimum</label>
                    <p class="text-gray-900">{{ $obat->stok_minimum }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga</label>
                    <p class="text-gray-900">Rp {{ number_format($obat->harga, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($obat->deskripsi)
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                <p class="text-gray-900">{{ $obat->deskripsi }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection