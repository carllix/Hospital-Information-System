@extends('layouts.dashboard')

@section('title', 'Detail Layanan | Admin Ganesha Hospital')
@section('dashboard-title', 'Detail Layanan')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <div class="mb-6">
        <a href="{{ route('admin.layanan.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-medium">Kembali ke Daftar Layanan</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Layanan</h2>
            </div>
            <a href="{{ route('admin.layanan.edit', $layanan->layanan_id) }}" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Edit
            </a>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kode Layanan</label>
                    <p class="text-gray-900">{{ $layanan->kode_layanan }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Layanan</label>
                    <p class="text-gray-900">{{ $layanan->nama_layanan }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <p class="text-gray-900">
                        @if($layanan->kategori === 'konsultasi')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                            Konsultasi
                        </span>
                        @else
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                            Tindakan
                        </span>
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga</label>
                    <p class="text-gray-900">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection