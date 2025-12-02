@extends('layouts.dashboard')

@section('title', 'Form Rujukan')

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
            <h1 class="text-3xl font-bold text-gray-900">Form Rujukan</h1>
            <p class="text-gray-600 mt-1">Pasien: <span class="font-semibold">{{ $pemeriksaan->pasien->nama_lengkap }}</span></p>
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

    <!-- Form Rujukan -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Formulir Rujukan</h2>
        </div>
        
        <form method="POST" action="{{ route('dokter.store-rujukan') }}" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->pemeriksaan_id }}">

            <!-- Tujuan Rujukan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tujuan Rujukan <span class="text-red-500">*</span>
                </label>
                <select name="tujuan_rujukan" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">-- Pilih Tujuan --</option>
                    <option value="rumah_sakit" {{ old('tujuan_rujukan', $pemeriksaan->rujukan->tujuan_rujukan ?? '') == 'rumah_sakit' ? 'selected' : '' }}>
                        Rumah Sakit
                    </option>
                    <option value="klinik_spesialis" {{ old('tujuan_rujukan', $pemeriksaan->rujukan->tujuan_rujukan ?? '') == 'klinik_spesialis' ? 'selected' : '' }}>
                        Klinik Spesialis
                    </option>
                    <option value="laboratorium" {{ old('tujuan_rujukan', $pemeriksaan->rujukan->tujuan_rujukan ?? '') == 'laboratorium' ? 'selected' : '' }}>
                        Laboratorium Khusus
                    </option>
                </select>
                @error('tujuan_rujukan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama RS/Klinik Tujuan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Rumah Sakit / Klinik Tujuan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="rs_tujuan" required
                    value="{{ old('rs_tujuan', $pemeriksaan->rujukan->rs_tujuan ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Contoh: RS Hasan Sadikin">
                @error('rs_tujuan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dokter Spesialis Tujuan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Dokter Spesialis Tujuan (Opsional)
                </label>
                <input type="text" name="dokter_spesialis_tujuan"
                    value="{{ old('dokter_spesialis_tujuan', $pemeriksaan->rujukan->dokter_spesialis_tujuan ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Contoh: dr. John Doe, Sp.PD">
            </div>

            <!-- Diagnosa Sementara -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Diagnosa Sementara <span class="text-red-500">*</span>
                </label>
                <textarea name="diagnosa_sementara" rows="3" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Diagnosa sementara...">{{ old('diagnosa_sementara', $pemeriksaan->rujukan->diagnosa_sementara ?? $pemeriksaan->diagnosa) }}</textarea>
                @error('diagnosa_sementara')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alasan Rujukan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Alasan Rujukan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_rujukan" rows="4" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Jelaskan alasan merujuk pasien ini...">{{ old('alasan_rujukan', $pemeriksaan->rujukan->alasan_rujukan ?? '') }}</textarea>
                @error('alasan_rujukan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex space-x-4 pt-4 border-t">
                <button type="submit" class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-semibold py-3 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                    Buat Surat Rujukan
                </button>
                <a href="{{ route('dokter.antrian') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Info -->
    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg">
        <div class="flex">
            <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <p class="text-sm text-purple-700 font-medium">Surat rujukan akan dibuat dan pasien dapat membawanya ke rumah sakit/klinik tujuan.</p>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection
