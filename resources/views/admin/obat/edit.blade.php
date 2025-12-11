@extends('layouts.dashboard')

@section('title', 'Edit Obat')
@section('dashboard-title', 'Edit Obat')

@section('content')
<x-toast type="error" :message="session('error')" />

<div class="mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Data Obat</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi obat</p>
            </div>
            <a href="{{ route('admin.obat.show', $obat->obat_id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.obat.update', $obat->obat_id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="kode_obat" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Obat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_obat" id="kode_obat" value="{{ old('kode_obat', $obat->kode_obat) }}" maxlength="20" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kode_obat') border-red-500 @enderror">
                    @error('kode_obat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_obat" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Obat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_obat" id="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nama_obat') border-red-500 @enderror">
                    @error('nama_obat')
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
                        <option value="tablet" {{ old('kategori', $obat->kategori) === 'tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="kapsul" {{ old('kategori', $obat->kategori) === 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                        <option value="sirup" {{ old('kategori', $obat->kategori) === 'sirup' ? 'selected' : '' }}>Sirup</option>
                        <option value="salep" {{ old('kategori', $obat->kategori) === 'salep' ? 'selected' : '' }}>Salep</option>
                        <option value="injeksi" {{ old('kategori', $obat->kategori) === 'injeksi' ? 'selected' : '' }}>Injeksi</option>
                        <option value="lainnya" {{ old('kategori', $obat->kategori) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('kategori')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                        Satuan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $obat->satuan) }}" maxlength="20" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('satuan') border-red-500 @enderror">
                    @error('satuan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stok" id="stok" value="{{ old('stok', $obat->stok) }}" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('stok') border-red-500 @enderror">
                    @error('stok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stok_minimum" class="block text-sm font-medium text-gray-700 mb-2">
                        Stok Minimum <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', $obat->stok_minimum) }}" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('stok_minimum') border-red-500 @enderror">
                    @error('stok_minimum')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $obat->harga) }}" min="0" step="0.01" required
                            class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('harga') border-red-500 @enderror">
                    </div>
                    @error('harga')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t">
                <a href="{{ route('admin.obat.show', $obat->obat_id) }}" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
