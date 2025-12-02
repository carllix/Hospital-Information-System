@extends('layouts.dashboard')

@section('title', 'Edit Obat')
@section('dashboard-title', 'Edit Obat')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('farmasi.update-obat', $obat->obat_id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Obat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Obat *</label>
                    <input type="text" name="kode_obat" value="{{ old('kode_obat', $obat->kode_obat) }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('kode_obat') border-red-500 @enderror">
                    @error('kode_obat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Obat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Obat *</label>
                    <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('nama_obat') border-red-500 @enderror">
                    @error('nama_obat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                    <select name="kategori" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('kategori') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="tablet" {{ old('kategori', $obat->kategori) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="kapsul" {{ old('kategori', $obat->kategori) == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                        <option value="sirup" {{ old('kategori', $obat->kategori) == 'sirup' ? 'selected' : '' }}>Sirup</option>
                        <option value="salep" {{ old('kategori', $obat->kategori) == 'salep' ? 'selected' : '' }}>Salep</option>
                        <option value="injeksi" {{ old('kategori', $obat->kategori) == 'injeksi' ? 'selected' : '' }}>Injeksi</option>
                    </select>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan *</label>
                    <input type="text" name="satuan" value="{{ old('satuan', $obat->satuan) }}" required placeholder="Contoh: Strip, Botol, Box"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('satuan') border-red-500 @enderror">
                    @error('satuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                    <input type="number" name="stok" value="{{ old('stok', $obat->stok) }}" min="0" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('stok') border-red-500 @enderror">
                    @error('stok')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) *</label>
                    <input type="number" name="harga" value="{{ old('harga', $obat->harga) }}" min="0" step="0.01" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('harga') border-red-500 @enderror">
                    @error('harga')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition font-medium">
                    Update Obat
                </button>
                <a href="{{ route('farmasi.stok-obat') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
