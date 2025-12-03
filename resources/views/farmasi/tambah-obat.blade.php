@extends('layouts.dashboard')

@section('title', 'Tambah Obat')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('farmasi.stok-obat') }}" class="p-2 bg-white rounded-full text-gray-600 hover:text-pink-600 hover:bg-pink-50 shadow-sm transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Obat Baru</h1>
            <p class="text-gray-500 text-sm">Masukkan detail obat untuk inventaris farmasi</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            <h2 class="font-bold text-white">Formulir Data Obat</h2>
        </div>

        <form method="POST" action="{{ route('farmasi.store-obat') }}" class="p-6 md:p-8 space-y-8">
            @csrf
            
            <div>
                <h3 class="text-gray-800 font-bold mb-4 flex items-center border-b border-gray-100 pb-2">
                    <span class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold mr-2">1</span>
                    Identitas Obat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_obat" value="{{ old('kode_obat') }}" required placeholder="Contoh: PCT-500"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-500 transition-all placeholder-gray-400 @error('kode_obat') border-red-500 @enderror">
                        @error('kode_obat')
                            <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_obat" value="{{ old('nama_obat') }}" required placeholder="Contoh: Paracetamol 500mg"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-500 transition-all placeholder-gray-400 @error('nama_obat') border-red-500 @enderror">
                        @error('nama_obat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="kategori" required 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-500 transition-all appearance-none bg-white @error('kategori') border-red-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="tablet" {{ old('kategori') == 'tablet' ? 'selected' : '' }}>💊 Tablet</option>
                                <option value="kapsul" {{ old('kategori') == 'kapsul' ? 'selected' : '' }}>💊 Kapsul</option>
                                <option value="sirup" {{ old('kategori') == 'sirup' ? 'selected' : '' }}>🥤 Sirup</option>
                                <option value="salep" {{ old('kategori') == 'salep' ? 'selected' : '' }}>🧴 Salep / Krim</option>
                                <option value="injeksi" {{ old('kategori') == 'injeksi' ? 'selected' : '' }}>💉 Injeksi / Ampul</option>
                                <option value="alat_kesehatan" {{ old('kategori') == 'alat_kesehatan' ? 'selected' : '' }}>🩺 Alat Kesehatan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @error('kategori')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="satuan" value="{{ old('satuan') }}" required placeholder="Contoh: Strip, Botol, Pcs"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-500 transition-all placeholder-gray-400 @error('satuan') border-red-500 @enderror">
                        @error('satuan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-gray-800 font-bold mb-4 flex items-center border-b border-gray-100 pb-2">
                    <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold mr-2">2</span>
                    Inventaris & Harga
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Awal <span class="text-red-500">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0" required 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-500 transition-all @error('stok') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Masukkan jumlah fisik yang tersedia saat ini.</p>
                        @error('stok')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual (Per Satuan) <span class="text-red-500">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                            </div>
                            <input type="number" name="harga" value="{{ old('harga') }}" min="0" step="100" required placeholder="0"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-500 transition-all @error('harga') border-red-500 @enderror">
                        </div>
                        @error('harga')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                 <h3 class="text-gray-800 font-bold mb-4 flex items-center border-b border-gray-100 pb-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-2">3</span>
                    Informasi Tambahan
                </h3>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Indikasi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Keterangan tambahan mengenai obat, dosis umum, atau efek samping..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-pink-500 transition-all placeholder-gray-400 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('farmasi.stok-obat') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium shadow-sm">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition font-medium shadow-lg shadow-pink-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Obat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection