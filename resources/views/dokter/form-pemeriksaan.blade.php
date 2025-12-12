@extends('layouts.dashboard')

@section('title', 'Form Pemeriksaan Pasien')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('dokter.antrian') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Form Pemeriksaan Pasien</h1>
            <p class="text-gray-600 mt-1">No. Antrian: <span class="font-semibold">{{ $pendaftaran->nomor_antrian }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Data Pasien Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Data Pasien</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $pendaftaran->pasien->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Rekam Medis</p>
                        <p class="font-semibold text-gray-900">{{ $pendaftaran->pasien->no_rm }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Jenis Kelamin</p>
                            <p class="font-semibold text-gray-900">{{ $pendaftaran->pasien->jenis_kelamin }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Usia</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pendaftaran->pasien->tanggal_lahir)->age }} tahun</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Lahir</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pendaftaran->pasien->tanggal_lahir)->isoFormat('D MMMM YYYY') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="font-semibold text-gray-900">{{ $pendaftaran->pasien->no_telepon }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Keluhan Utama</p>
                        <p class="font-semibold text-gray-900">{{ $pendaftaran->keluhan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pemeriksaan -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Formulir Pemeriksaan</h2>
                </div>
                <form method="POST" action="{{ route('dokter.store-pemeriksaan') }}" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->pendaftaran_id }}">

                    <!-- Anamnesa -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Anamnesa <span class="text-red-500">*</span>
                        </label>
                        <textarea name="anamnesa" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            placeholder="Keluhan dan riwayat penyakit pasien...">{{ old('anamnesa', $pemeriksaan->anamnesa ?? '') }}</textarea>
                        @error('anamnesa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pemeriksaan Fisik -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pemeriksaan Fisik</label>
                        <textarea name="pemeriksaan_fisik" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            placeholder="Hasil pemeriksaan fisik...">{{ old('pemeriksaan_fisik', $pemeriksaan->pemeriksaan_fisik ?? '') }}</textarea>
                    </div>

                    <!-- Vital Signs -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tekanan Darah</label>
                            <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah', $pemeriksaan->tekanan_darah ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                placeholder="120/80">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Suhu (°C)</label>
                            <input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh', $pemeriksaan->suhu_tubuh ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                placeholder="36.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Berat (kg)</label>
                            <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $pemeriksaan->berat_badan ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                placeholder="65.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tinggi (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $pemeriksaan->tinggi_badan ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                placeholder="170">
                        </div>
                    </div>

                    <!-- Diagnosa -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Diagnosa <span class="text-red-500">*</span>
                        </label>
                        <textarea name="diagnosa" rows="3" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            placeholder="Diagnosa penyakit...">{{ old('diagnosa', $pemeriksaan->diagnosa ?? '') }}</textarea>
                        @error('diagnosa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ICD-10 Code -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode ICD-10</label>
                        <input type="text" name="icd10_code" value="{{ old('icd10_code', $pemeriksaan->icd10_code ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            placeholder="J00 (contoh)">
                    </div>

                    <!-- Tindakan Medis -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tindakan Medis</label>
                        <textarea name="tindakan_medis" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            placeholder="Tindakan yang dilakukan...">{{ old('tindakan_medis', $pemeriksaan->tindakan_medis ?? '') }}</textarea>
                    </div>

                    <!-- Catatan Dokter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Dokter</label>
                        <textarea name="catatan_dokter" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            placeholder="Catatan tambahan...">{{ old('catatan_dokter', $pemeriksaan->catatan_dokter ?? '') }}</textarea>
                    </div>

                    <!-- Status Pasien -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status Pasien <span class="text-red-500">*</span>
                        </label>
                        <select name="status_pasien" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">-- Pilih Status --</option>
                            <option value="selesai_penanganan" {{ old('status_pasien', $pemeriksaan->status_pasien ?? '') == 'selesai_penanganan' ? 'selected' : '' }}>
                                Selesai Penanganan
                            </option>
                            <option value="perlu_resep" {{ old('status_pasien', $pemeriksaan->status_pasien ?? '') == 'perlu_resep' ? 'selected' : '' }}>
                                Perlu Resep Obat
                            </option>
                            <option value="perlu_lab" {{ old('status_pasien', $pemeriksaan->status_pasien ?? '') == 'perlu_lab' ? 'selected' : '' }}>
                                Perlu Pemeriksaan Lab
                            </option>
                            <option value="dirujuk" {{ old('status_pasien', $pemeriksaan->status_pasien ?? '') == 'dirujuk' ? 'selected' : '' }}>
                                Dirujuk ke Spesialis/RS
                            </option>
                        </select>
                        @error('status_pasien')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex space-x-4 pt-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold py-3 rounded-lg hover:from-pink-600 hover:to-pink-700 transition-all duration-200 shadow-lg">
                            Simpan Pemeriksaan
                        </button>
                        <a href="{{ route('dokter.antrian') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Riwayat Pemeriksaan -->
            @if($riwayatPemeriksaan->isNotEmpty())
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white">Riwayat Pemeriksaan (3 Terakhir)</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($riwayatPemeriksaan as $riwayat)
                            <div class="border-l-4 border-purple-500 pl-4 py-2">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($riwayat->tanggal_pemeriksaan)->isoFormat('D MMMM YYYY') }}</p>
                                            <p class="text-sm text-gray-600">oleh {{ $riwayat->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? 'Dokter' }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                        {{ ucwords(str_replace('_', ' ', $riwayat->status_pasien)) }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-sm">
                                    <p><span class="font-semibold">Diagnosa:</span> {{ $riwayat->diagnosa }}</p>
                                    @if($riwayat->tekanan_darah)
                                        <p><span class="font-semibold">TD:</span> {{ $riwayat->tekanan_darah }} mmHg</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection
