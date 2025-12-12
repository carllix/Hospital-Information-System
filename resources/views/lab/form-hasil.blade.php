@extends('layouts.dashboard')

@section('title', 'Input Hasil Lab')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Input Hasil Pemeriksaan</h1>
            <p class="text-sm text-gray-500 mt-1">Masukkan hasil analisa laboratorium untuk pasien.</p>
        </div>
        <a href="{{ route('lab.daftar-permintaan') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>

    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <x-toast :type="session('success') ? 'success' : 'error'" :message="session('success') ?? session('error')" />
    @endif

    {{-- Info Pasien Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100">
            <div class="h-12 w-12 rounded-full bg-pink-50 flex items-center justify-center text-pink-600 font-bold text-xl">
                {{-- PERBAIKAN: Akses inisial nama --}}
                {{ substr($permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '?', 0, 1) }}
            </div>
            <div>
                {{-- PERBAIKAN: Akses Nama Pasien --}}
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}
                </h3>
                {{-- PERBAIKAN: Akses No RM --}}
                <p class="text-sm text-gray-500">
                    RM: {{ $permintaan->pemeriksaan->pendaftaran->pasien->no_rm ?? $permintaan->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}
                </p>
            </div>
            <div class="ml-auto text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                    {{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}
                </span>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $permintaan->tanggal_permintaan ? $permintaan->tanggal_permintaan->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                <p class="font-semibold text-gray-700">
                    {{-- PERBAIKAN: Akses Gender --}}
                    {{ ($permintaan->pemeriksaan->pendaftaran->pasien->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Umur</label>
                <p class="font-semibold text-gray-700">
                    {{-- PERBAIKAN: Hitung Umur --}}
                    {{ $permintaan->pemeriksaan->pendaftaran->pasien ? \Carbon\Carbon::parse($permintaan->pemeriksaan->pendaftaran->pasien->tanggal_lahir)->age . ' Tahun' : '-' }}
                </p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Dokter Pengirim</label>
                <p class="font-semibold text-gray-700">
                    {{-- PERBAIKAN: Akses Dokter --}}
                    {{ $permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}
                </p>
            </div>
            @if($permintaan->catatan_permintaan)
            <div class="col-span-2 md:col-span-4 mt-2">
                <div class="bg-amber-50 rounded-lg p-3 border border-amber-100 flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-xs font-bold text-amber-800 uppercase">Catatan Dokter</p>
                        <p class="text-gray-700">{{ $permintaan->catatan_permintaan }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Form Input Hasil --}}
    <form action="{{ route('lab.store-hasil') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="permintaan_lab_id" value="{{ $permintaan->permintaan_lab_id }}">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Parameter Pemeriksaan
            </h3>

            <div id="hasil-container" class="space-y-4">
                @foreach($parameterTemplates as $index => $template)
                <div class="hasil-item bg-gray-50/50 border border-gray-200 rounded-xl p-5 relative transition-all hover:border-pink-200 hover:shadow-sm group">
                    {{-- Tombol Hapus (Hanya muncul jika bukan item pertama) --}}
                    @if($index > 0)
                    <button type="button" onclick="removeHasil(this)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Parameter <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="hasil[{{ $index }}][parameter]" 
                                   value="{{ old("hasil.$index.parameter", $template['parameter']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow" 
                                   placeholder="Contoh: Hemoglobin"
                                   required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Hasil <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="hasil[{{ $index }}][hasil_nilai]" 
                                   value="{{ old("hasil.$index.hasil_nilai") }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow font-medium" 
                                   placeholder="Hasil pemeriksaan"
                                   required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Satuan</label>
                            <input type="text" 
                                   name="hasil[{{ $index }}][satuan]" 
                                   value="{{ old("hasil.$index.satuan", $template['satuan']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow bg-gray-50"
                                   placeholder="mg/dL">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nilai Normal</label>
                            <input type="text" 
                                   name="hasil[{{ $index }}][nilai_normal]" 
                                   value="{{ old("hasil.$index.nilai_normal", $template['nilai_normal']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow text-gray-500 text-sm">
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan / Catatan</label>
                            <textarea name="hasil[{{ $index }}][keterangan]" 
                                      rows="1"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow text-sm"
                                      placeholder="Opsional">{{ old("hasil.$index.keterangan") }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" onclick="addHasil()" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 font-medium hover:border-pink-500 hover:text-pink-600 hover:bg-pink-50 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Parameter Lain
            </button>
        </div>

        {{-- Upload File --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Lampiran Dokumen (Opsional)</h3>
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-pink-400 transition-all">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-sm text-gray-500"><span class="font-semibold text-pink-600">Klik untuk upload</span> atau drag and drop</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, PNG, JPG (Maks. 2MB)</p>
                    </div>
                    <input id="dropzone-file" type="file" name="file_hasil" accept=".pdf,.jpg,.jpeg,.png" class="hidden" />
                </label>
            </div> 
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-end gap-3 mt-8">
            <a href="{{ route('lab.daftar-permintaan') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-medium rounded-lg hover:from-pink-600 hover:to-pink-700 shadow-lg shadow-pink-200 transition-all transform hover:-translate-y-0.5">
                Simpan & Selesaikan
            </button>
        </div>
    </form>
</div>

<script>
let hasilIndex = {{ count($parameterTemplates) }};

function addHasil() {
    const container = document.getElementById('hasil-container');
    const newItem = document.createElement('div');
    // Kita gunakan class yang sama persis dengan yang ada di blade
    newItem.className = 'hasil-item bg-gray-50/50 border border-gray-200 rounded-xl p-5 relative transition-all hover:border-pink-200 hover:shadow-sm group';
    
    newItem.innerHTML = `
        <button type="button" onclick="removeHasil(this)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Parameter <span class="text-red-500">*</span></label>
                <input type="text" name="hasil[${hasilIndex}][parameter]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow" placeholder="Nama parameter" required>
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Hasil <span class="text-red-500">*</span></label>
                <input type="text" name="hasil[${hasilIndex}][hasil_nilai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow font-medium" placeholder="Hasil" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Satuan</label>
                <input type="text" name="hasil[${hasilIndex}][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow bg-gray-50" placeholder="Satuan">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nilai Normal</label>
                <input type="text" name="hasil[${hasilIndex}][nilai_normal]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow text-gray-500 text-sm">
            </div>
            <div class="md:col-span-12">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan / Catatan</label>
                <textarea name="hasil[${hasilIndex}][keterangan]" rows="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-shadow text-sm" placeholder="Opsional"></textarea>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    hasilIndex++;
}

function removeHasil(button) {
    button.closest('.hasil-item').remove();
}
</script>
@endsection