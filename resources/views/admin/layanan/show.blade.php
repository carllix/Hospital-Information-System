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
                <a href="{{ route('admin.layanan.edit', $layanan->layanan_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                    Edit
                </a>
                <form action="{{ route('admin.layanan.destroy', $layanan->layanan_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
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

            <div class="md:col-span-2 pt-4 border-t">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Ditambahkan:</span>
                        <span class="ml-2">{{ $layanan->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Terakhir Diupdate:</span>
                        <span class="ml-2">{{ $layanan->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
