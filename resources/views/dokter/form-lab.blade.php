@extends('layouts.dashboard')

@section('title', 'Form Permintaan Lab')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('dokter.antrian') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Form Permintaan Laboratorium</h1>
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

    <!-- Form Permintaan Lab -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Pilih Jenis Pemeriksaan Lab</h2>
        </div>
        
        <form method="POST" action="{{ route('dokter.store-lab') }}" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->pemeriksaan_id }}">

            <!-- Jenis Test Lab -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Jenis Pemeriksaan <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($jenisTestLab as $test)
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 cursor-pointer transition-colors">
                            <input type="checkbox" name="jenis_test[]" value="{{ $test }}" 
                                {{ in_array($test, old('jenis_test', $pemeriksaan->permintaanLab->pluck('jenis_test')->toArray())) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                            <span class="ml-3 text-gray-700 font-medium">{{ $test }}</span>
                        </label>
                    @endforeach
                </div>
                @error('jenis_test')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan Dokter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan untuk Laboratorium</label>
                <textarea name="catatan_dokter" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Catatan khusus untuk pemeriksaan lab...">{{ old('catatan_dokter', $pemeriksaan->permintaanLab->first()->catatan_dokter ?? '') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex space-x-4 pt-4 border-t">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg">
                    Kirim Permintaan Lab
                </button>
                <a href="{{ route('dokter.antrian') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Info -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <p class="text-sm text-blue-700 font-medium">Permintaan lab akan dikirim ke bagian laboratorium untuk diproses.</p>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection
