@extends('layouts.dashboard')

@section('title', 'Input Hasil Lab')
@section('dashboard-title', 'Input Hasil Pemeriksaan Lab')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <x-toast :type="session('success') ? 'success' : 'error'" :message="session('success') ?? session('error')" />
    @endif

    {{-- Info Pasien --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pasien</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm text-gray-600">Nama Pasien</label>
                <p class="font-medium text-gray-900">{{ $permintaan->pasien->nama_lengkap }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">No. Rekam Medis</label>
                <p class="font-medium text-gray-900">{{ $permintaan->pasien->no_rekam_medis }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Jenis Kelamin</label>
                <p class="font-medium text-gray-900">{{ ucfirst($permintaan->pasien->jenis_kelamin) }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Umur</label>
                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($permintaan->pasien->tanggal_lahir)->age }} tahun</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Dokter Pengirim</label>
                <p class="font-medium text-gray-900">{{ $permintaan->dokter->nama_lengkap }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Jenis Pemeriksaan</label>
                <p class="font-medium text-pink-600">{{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}</p>
            </div>
        </div>
        @if($permintaan->catatan_permintaan)
        <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400">
            <p class="text-sm font-medium text-yellow-800">Catatan Dokter:</p>
            <p class="text-sm text-yellow-700 mt-1">{{ $permintaan->catatan_permintaan }}</p>
        </div>
        @endif
    </div>

    {{-- Form Input Hasil --}}
    <form action="{{ route('lab.store-hasil') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        <input type="hidden" name="permintaan_lab_id" value="{{ $permintaan->permintaan_lab_id }}">

        <h3 class="text-lg font-semibold text-gray-800 mb-6">Input Hasil Pemeriksaan</h3>

        <div id="hasil-container" class="space-y-4">
            @foreach($parameterTemplates as $index => $template)
            <div class="hasil-item border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parameter <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="hasil[{{ $index }}][parameter]" 
                               value="{{ old("hasil.$index.parameter", $template['parameter']) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500" 
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hasil <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="hasil[{{ $index }}][hasil_nilai]" 
                               value="{{ old("hasil.$index.hasil_nilai") }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500" 
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <input type="text" 
                               name="hasil[{{ $index }}][satuan]" 
                               value="{{ old("hasil.$index.satuan", $template['satuan']) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Normal</label>
                        <input type="text" 
                               name="hasil[{{ $index }}][nilai_normal]" 
                               value="{{ old("hasil.$index.nilai_normal", $template['nilai_normal']) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="hasil[{{ $index }}][keterangan]" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500">{{ old("hasil.$index.keterangan") }}</textarea>
                    </div>
                </div>
                @if($index > 0)
                <button type="button" onclick="removeHasil(this)" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                    Hapus Parameter
                </button>
                @endif
            </div>
            @endforeach
        </div>

        <button type="button" onclick="addHasil()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            + Tambah Parameter
        </button>

        {{-- Upload File Hasil (Opsional) --}}
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Hasil (Opsional)</label>
            <input type="file" 
                   name="file_hasil" 
                   accept=".pdf,.jpg,.jpeg,.png"
                   class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-md file:border-0
                          file:text-sm file:font-semibold
                          file:bg-pink-50 file:text-pink-700
                          hover:file:bg-pink-100">
            <p class="mt-1 text-sm text-gray-500">Format: PDF, JPG, JPEG, PNG. Max: 2MB</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('lab.daftar-permintaan') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition-colors">
                Simpan Hasil Lab
            </button>
        </div>
    </form>
</div>

<script>
let hasilIndex = {{ count($parameterTemplates) }};

function addHasil() {
    const container = document.getElementById('hasil-container');
    const newItem = document.createElement('div');
    newItem.className = 'hasil-item border border-gray-200 rounded-lg p-4';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parameter <span class="text-red-500">*</span></label>
                <input type="text" name="hasil[${hasilIndex}][parameter]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasil <span class="text-red-500">*</span></label>
                <input type="text" name="hasil[${hasilIndex}][hasil_nilai]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="hasil[${hasilIndex}][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Normal</label>
                <input type="text" name="hasil[${hasilIndex}][nilai_normal]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500">
            </div>
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="hasil[${hasilIndex}][keterangan]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-pink-500 focus:border-pink-500"></textarea>
            </div>
        </div>
        <button type="button" onclick="removeHasil(this)" class="mt-2 text-red-600 hover:text-red-800 text-sm">
            Hapus Parameter
        </button>
    `;
    container.appendChild(newItem);
    hasilIndex++;
}

function removeHasil(button) {
    button.closest('.hasil-item').remove();
}
</script>
@endsection
