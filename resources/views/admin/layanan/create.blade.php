@extends('layouts.dashboard')

@section('title', 'Tambah Layanan | Admin Ganesha Hospital')
@section('dashboard-title', 'Tambah Layanan')

@section('content')
<x-toast type="error" :message="session('error')" />

<div class="mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Tambah Layanan Baru</h2>
                <p class="text-sm text-gray-600 mt-1">Lengkapi form di bawah untuk menambahkan layanan</p>
            </div>
            <a href="{{ route('admin.layanan.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.layanan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="kode_layanan" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Layanan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_layanan" id="kode_layanan" value="{{ old('kode_layanan') }}" maxlength="20" required
                        placeholder="Contoh: LAY001"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kode_layanan') border-red-500 @enderror">
                    @error('kode_layanan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" id="kategori" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kategori') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="konsultasi" {{ old('kategori') === 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                        <option value="tindakan" {{ old('kategori') === 'tindakan' ? 'selected' : '' }}>Tindakan</option>
                    </select>
                    @error('kategori')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Konsultasi: Pemeriksaan dokter | Tindakan: Prosedur medis</p>
                </div>

                <div class="md:col-span-2">
                    <label for="nama_layanan" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Layanan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan') }}" required
                        placeholder="Contoh: Konsultasi Dokter Umum"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nama_layanan') border-red-500 @enderror">
                    @error('nama_layanan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" min="0" step="0.01" required placeholder="Masukkan harga"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('harga') border-red-500 @enderror">
                    @error('harga')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t">
                <a href="{{ route('admin.layanan.index') }}" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection