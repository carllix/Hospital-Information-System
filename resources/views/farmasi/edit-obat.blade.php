@extends('layouts.dashboard')

@section('title', 'Edit Obat')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('farmasi.stok-obat') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Data Obat</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi obat dalam inventori</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Formulir Edit Obat</h2>
        </div>

        <form method="POST" action="{{ route('farmasi.update-obat', $obat->obat_id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Identitas Obat -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Identitas Obat
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_obat" value="{{ old('kode_obat', $obat->kode_obat) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('kode_obat') border-red-500 @enderror"
                            placeholder="Cth: PAR-500">
                        @error('kode_obat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('nama_obat') border-red-500 @enderror"
                            placeholder="Cth: Paracetamol 500mg">
                        @error('nama_obat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent appearance-none bg-white @error('kategori') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="tablet" {{ old('kategori', $obat->kategori) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="kapsul" {{ old('kategori', $obat->kategori) == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                                <option value="sirup" {{ old('kategori', $obat->kategori) == 'sirup' ? 'selected' : '' }}>Sirup</option>
                                <option value="salep" {{ old('kategori', $obat->kategori) == 'salep' ? 'selected' : '' }}>Salep</option>
                                <option value="injeksi" {{ old('kategori', $obat->kategori) == 'injeksi' ? 'selected' : '' }}>Injeksi</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                        @error('kategori') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="satuan" value="{{ old('satuan', $obat->satuan) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('satuan') border-red-500 @enderror"
                            placeholder="Contoh: Strip, Botol">
                        @error('satuan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Inventori & Harga -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Inventori & Harga
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Saat Ini <span class="text-red-500">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', $obat->stok) }}" min="0" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('stok') border-red-500 @enderror">
                        @error('stok') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">Rp</span>
                            </div>
                            <input type="number" name="harga" value="{{ old('harga', $obat->harga) }}" min="0" step="100" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('harga') border-red-500 @enderror">
                        </div>
                        @error('harga') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Informasi Tambahan
                </h3>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Indikasi</label>
                    <textarea name="deskripsi" rows="4" placeholder="Keterangan tambahan mengenai obat..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('farmasi.stok-obat') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection