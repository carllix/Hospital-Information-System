@extends('layouts.dashboard')

@section('title', 'Form Resep Obat')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('dokter.antrian') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Form Resep Obat</h1>
            <p class="text-gray-600 mt-1">Pasien: <span class="font-semibold">{{ $pemeriksaan->pendaftaran->pasien->nama_lengkap }}</span></p>
        </div>
    </div>

    <!-- Data Pemeriksaan -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Data Pemeriksaan</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Diagnosa</p>
                <p class="font-semibold text-gray-900">{{ $pemeriksaan->diagnosa }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kode ICD-10</p>
                <p class="font-semibold text-gray-900">{{ $pemeriksaan->icd10_code ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Pemeriksaan</p>
                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->isoFormat('D MMMM YYYY') }}</p>
            </div>
        </div>
    </div>

    <!-- Form Resep -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Daftar Obat</h2>
        </div>
        
        <form method="POST" action="{{ route('dokter.store-resep') }}" class="p-6">
            @csrf
            <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->pemeriksaan_id }}">

            <div id="obat-container" class="space-y-4">
                <!-- Template Obat akan ditambahkan di sini -->
                @if($pemeriksaan->resep && $pemeriksaan->resep->detailResep->isNotEmpty())
                    @foreach($pemeriksaan->resep->detailResep as $index => $detail)
                        <div class="obat-item border-2 border-gray-200 rounded-lg p-4 relative">
                            <button type="button" onclick="removeObat(this)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat</label>
                                    <select name="obat[{{ $index }}][obat_id]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach($obatList as $obat)
                                            <option value="{{ $obat->obat_id }}" {{ $detail->obat_id == $obat->obat_id ? 'selected' : '' }}>
                                                {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                                    <input type="number" name="obat[{{ $index }}][jumlah]" value="{{ $detail->jumlah }}" min="1" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                        placeholder="Jumlah">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Aturan Pakai</label>
                                    <input type="text" name="obat[{{ $index }}][aturan_pakai]" value="{{ $detail->aturan_pakai }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                        placeholder="3x sehari 1 tablet">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="obat-item border-2 border-gray-200 rounded-lg p-4 relative">
                        <button type="button" onclick="removeObat(this)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat</label>
                                <select name="obat[0][obat_id]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach($obatList as $obat)
                                        <option value="{{ $obat->obat_id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                                <input type="number" name="obat[0][jumlah]" min="1" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                    placeholder="Jumlah">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Aturan Pakai</label>
                                <input type="text" name="obat[0][aturan_pakai]" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                    placeholder="3x sehari 1 tablet">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <button type="button" onclick="addObat()" class="mt-4 px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-colors">
                + Tambah Obat
            </button>

            @error('obat')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

            <div class="flex space-x-4 mt-6 pt-6 border-t">
                <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg">
                    Simpan Resep
                </button>
                <a href="{{ route('dokter.antrian') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let obatIndex = {{ $pemeriksaan->resep ? $pemeriksaan->resep->detailResep->count() : 1 }};

function addObat() {
    const container = document.getElementById('obat-container');
    const obatItem = document.createElement('div');
    obatItem.className = 'obat-item border-2 border-gray-200 rounded-lg p-4 relative';
    obatItem.innerHTML = `
        <button type="button" onclick="removeObat(this)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat</label>
                <select name="obat[${obatIndex}][obat_id]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">-- Pilih Obat --</option>
                    @foreach($obatList as $obat)
                        <option value="{{ $obat->obat_id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                <input type="number" name="obat[${obatIndex}][jumlah]" min="1" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Jumlah">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Aturan Pakai</label>
                <input type="text" name="obat[${obatIndex}][aturan_pakai]" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="3x sehari 1 tablet">
            </div>
        </div>
    `;
    container.appendChild(obatItem);
    obatIndex++;
}

function removeObat(button) {
    const items = document.querySelectorAll('.obat-item');
    if (items.length > 1) {
        button.closest('.obat-item').remove();
    } else {
        alert('Harus ada minimal 1 obat dalam resep!');
    }
}
</script>

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection
