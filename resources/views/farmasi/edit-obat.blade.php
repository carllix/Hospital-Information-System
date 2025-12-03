@extends('layouts.dashboard')

@section('title', 'Edit Obat')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Data Obat</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi obat dalam inventori.</p>
        </div>
        <a href="{{ route('farmasi.stok-obat') }}" class="text-sm text-gray-500 hover:text-pink-600 font-medium transition-colors">
            &larr; Kembali ke Stok
        </a>
    </div>

    <form method="POST" action="{{ route('farmasi.update-obat', $obat->obat_id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Card 1: Identitas Obat --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    Identitas Obat
                </h3>
                
                <div class="space-y-4">
                    {{-- Kode Obat --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_obat" value="{{ old('kode_obat', $obat->kode_obat) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow @error('kode_obat') border-red-500 @enderror"
                               placeholder="Cth: PAR-500">
                        @error('kode_obat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Obat --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow font-medium text-gray-900 @error('nama_obat') border-red-500 @enderror"
                               placeholder="Cth: Paracetamol 500mg">
                        @error('nama_obat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="kategori" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 appearance-none bg-white cursor-pointer @error('kategori') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="tablet" {{ old('kategori', $obat->kategori) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="kapsul" {{ old('kategori', $obat->kategori) == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                                <option value="sirup" {{ old('kategori', $obat->kategori) == 'sirup' ? 'selected' : '' }}>Sirup</option>
                                <option value="salep" {{ old('kategori', $obat->kategori) == 'salep' ? 'selected' : '' }}>Salep</option>
                                <option value="injeksi" {{ old('kategori', $obat->kategori) == 'injeksi' ? 'selected' : '' }}>Injeksi</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('kategori') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow text-sm @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Keterangan tambahan obat...">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Inventori & Harga --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Inventori & Harga
                    </h3>

                    <div class="space-y-4">
                        {{-- Satuan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                            <input type="text" name="satuan" value="{{ old('satuan', $obat->satuan) }}" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow @error('satuan') border-red-500 @enderror"
                                   placeholder="Contoh: Strip, Botol">
                            @error('satuan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Saat Ini <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="stok" value="{{ old('stok', $obat->stok) }}" min="0" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow font-mono text-lg @error('stok') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">Unit</span>
                                </div>
                            </div>
                            @error('stok') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">Rp</span>
                                </div>
                                <input type="number" name="harga" value="{{ old('harga', $obat->harga) }}" min="0" step="0.01" required
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow font-mono text-lg @error('harga') border-red-500 @enderror">
                            </div>
                            @error('harga') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 py-3 px-4 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-bold rounded-xl shadow-lg shadow-pink-200 hover:from-pink-600 hover:to-pink-700 transition-all transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('farmasi.stok-obat') }}" class="py-3 px-6 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection