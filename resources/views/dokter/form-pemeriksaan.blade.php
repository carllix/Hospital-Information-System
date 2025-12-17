@extends('layouts.dashboard')

@section('title', 'Form Pemeriksaan Pasien')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('dokter.antrian') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
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
            <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Data Pasien</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $pendaftaran->pasien->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Rekam Medis</p>
                        <p class="font-semibold text-gray-900">{{ $pendaftaran->pasien->no_rekam_medis }}</p>
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
                        <p class="font-semibold text-gray-900">{{ $pendaftaran->keluhan_utama ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pemeriksaan -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Formulir Pemeriksaan</h2>
                </div>
                <form method="POST" action="{{ route('dokter.store-pemeriksaan') }}" class="p-6 space-y-6" id="form-pemeriksaan">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->pendaftaran_id }}">

                    <!-- Anamnesa -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Anamnesa <span class="text-red-500">*</span>
                        </label>
                        <textarea name="anamnesa" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('anamnesa') border-red-500 @enderror"
                            placeholder="Keluhan dan riwayat penyakit pasien...">{{ old('anamnesa', $pemeriksaan->anamnesa ?? '') }}</textarea>
                        @error('anamnesa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pemeriksaan Fisik -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pemeriksaan Fisik</label>
                        <textarea name="pemeriksaan_fisik" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('pemeriksaan_fisik') border-red-500 @enderror"
                            placeholder="Hasil pemeriksaan fisik...">{{ old('pemeriksaan_fisik', $pemeriksaan->pemeriksaan_fisik ?? '') }}</textarea>
                        @error('pemeriksaan_fisik')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vital Signs -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tekanan Darah</label>
                            <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah', $pemeriksaan->tekanan_darah ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tekanan_darah') border-red-500 @enderror"
                                placeholder="120/80">
                            @error('tekanan_darah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Suhu (°C)</label>
                            <input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh', $pemeriksaan->suhu_tubuh ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('suhu_tubuh') border-red-500 @enderror"
                                placeholder="36.5">
                            @error('suhu_tubuh')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Berat (kg)</label>
                            <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $pemeriksaan->berat_badan ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('berat_badan') border-red-500 @enderror"
                                placeholder="65.5">
                            @error('berat_badan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tinggi (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $pemeriksaan->tinggi_badan ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tinggi_badan') border-red-500 @enderror"
                                placeholder="170">
                            @error('tinggi_badan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Diagnosa -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Diagnosa <span class="text-red-500">*</span>
                        </label>
                        <textarea name="diagnosa" rows="3" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('diagnosa') border-red-500 @enderror"
                            placeholder="Diagnosa penyakit...">{{ old('diagnosa', $pemeriksaan->diagnosa ?? '') }}</textarea>
                        @error('diagnosa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ICD-10 Code -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode ICD-10</label>
                        <input type="text" name="icd10_code" value="{{ old('icd10_code', $pemeriksaan->icd10_code ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('icd10_code') border-red-500 @enderror"
                            placeholder="J00 (contoh)">
                        @error('icd10_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tindakan Medis -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tindakan Medis</label>
                        <textarea name="tindakan_medis" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('tindakan_medis') border-red-500 @enderror"
                            placeholder="Tindakan yang dilakukan...">{{ old('tindakan_medis', $pemeriksaan->tindakan_medis ?? '') }}</textarea>
                        @error('tindakan_medis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Dokter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Dokter</label>
                        <textarea name="catatan_dokter" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent @error('catatan_dokter') border-red-500 @enderror"
                            placeholder="Catatan tambahan...">{{ old('catatan_dokter', $pemeriksaan->catatan_dokter ?? '') }}</textarea>
                        @error('catatan_dokter')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tindak Lanjut</h3>

                        <!-- Tindak Lanjut Options -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="selesai" class="peer sr-only" {{ old('tindak_lanjut', 'selesai') == 'selesai' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-[#f56e9d] peer-checked:bg-[#f56e9d]/10 peer-checked:shadow-md hover:border-[#f56e9d] hover:bg-[#f56e9d]/10 hover:shadow-md transition-all">
                                    <span class="text-sm font-semibold text-gray-700">Selesai</span>
                                    <p class="text-xs text-gray-500 mt-1">Tanpa tindak lanjut</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="resep" class="peer sr-only" {{ old('tindak_lanjut') == 'resep' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-[#f56e9d] peer-checked:bg-[#f56e9d]/10 peer-checked:shadow-md hover:border-[#f56e9d] hover:bg-[#f56e9d]/10 hover:shadow-md transition-all">
                                    <span class="text-sm font-semibold text-gray-700">Buat Resep</span>
                                    <p class="text-xs text-gray-500 mt-1">Resep obat</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="lab" class="peer sr-only" {{ old('tindak_lanjut') == 'lab' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-[#f56e9d] peer-checked:bg-[#f56e9d]/10 peer-checked:shadow-md hover:border-[#f56e9d] hover:bg-[#f56e9d]/10 hover:shadow-md transition-all">
                                    <span class="text-sm font-semibold text-gray-700">Permintaan Lab</span>
                                    <p class="text-xs text-gray-500 mt-1">Pemeriksaan lab</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="rujukan" class="peer sr-only" {{ old('tindak_lanjut') == 'rujukan' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-[#f56e9d] peer-checked:bg-[#f56e9d]/10 peer-checked:shadow-md hover:border-[#f56e9d] hover:bg-[#f56e9d]/10 hover:shadow-md transition-all">
                                    <span class="text-sm font-semibold text-gray-700">Buat Rujukan</span>
                                    <p class="text-xs text-gray-500 mt-1">Rujuk ke RS/Spesialis</p>
                                </div>
                            </label>
                        </div>
                        @error('tindak_lanjut')
                        <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                        @enderror

                        <!-- Form Resep (hidden by default) -->
                        <div id="form-resep" class="hidden border-2 border-gray-200 rounded-lg p-6 bg-gray-50 space-y-4">
                            <h4 class="font-bold text-gray-800">
                                Resep Obat
                            </h4>

                            <div id="obat-container" class="space-y-3">
                                @if(old('obat'))
                                    @foreach(old('obat') as $index => $oldObat)
                                    <div class="obat-item bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                                                <select name="obat[{{ $index }}][obat_id]" class="obat-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d] @error('obat.'.$index.'.obat_id') border-red-500 @enderror">
                                                    <option value="">Pilih Obat</option>
                                                    @foreach($obatList as $obat)
                                                    <option value="{{ $obat->obat_id }}" {{ $oldObat['obat_id'] == $obat->obat_id ? 'selected' : '' }}>{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah <span class="text-red-500">*</span></label>
                                                <input type="number" name="obat[{{ $index }}][jumlah]" min="1" value="{{ $oldObat['jumlah'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d] @error('obat.'.$index.'.jumlah') border-red-500 @enderror" placeholder="10">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Dosis <span class="text-red-500">*</span></label>
                                                <input type="text" name="obat[{{ $index }}][dosis]" value="{{ $oldObat['dosis'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d] @error('obat.'.$index.'.dosis') border-red-500 @enderror" placeholder="500mg">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Aturan Pakai <span class="text-red-500">*</span></label>
                                                <input type="text" name="obat[{{ $index }}][aturan_pakai]" value="{{ $oldObat['aturan_pakai'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d] @error('obat.'.$index.'.aturan_pakai') border-red-500 @enderror" placeholder="3x1 sehari">
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeObat(this)" class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium {{ count(old('obat')) > 1 ? '' : 'hidden' }}">
                                            Hapus Obat
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                <div class="obat-item bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                                            <select name="obat[0][obat_id]" class="obat-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]">
                                                <option value="">Pilih Obat</option>
                                                @foreach($obatList as $obat)
                                                <option value="{{ $obat->obat_id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah <span class="text-red-500">*</span></label>
                                            <input type="number" name="obat[0][jumlah]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]" placeholder="10">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Dosis <span class="text-red-500">*</span></label>
                                            <input type="text" name="obat[0][dosis]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]" placeholder="500mg">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Aturan Pakai <span class="text-red-500">*</span></label>
                                            <input type="text" name="obat[0][aturan_pakai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]" placeholder="3x1 sehari">
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeObat(this)" class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium hidden">
                                        Hapus Obat
                                    </button>
                                </div>
                                @endif
                            </div>

                            <button type="button" onclick="addObat()" class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors text-sm font-medium">
                                Tambah Obat
                            </button>

                            @error('obat')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Lab (hidden by default) -->
                        <div id="form-lab" class="hidden border-2 border-gray-200 rounded-lg p-6 bg-gray-50 space-y-4">
                            <h4 class="font-bold text-gray-800">
                                Permintaan Pemeriksaan Lab
                            </h4>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pemeriksaan <span class="text-red-500">*</span></label>
                                <select name="jenis_pemeriksaan_lab" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] bg-white @error('jenis_pemeriksaan_lab') border-red-500 @enderror">
                                    <option value="">Pilih Jenis Pemeriksaan</option>
                                    @foreach($jenisTestLab as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_pemeriksaan_lab') == $jenis ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $jenis)) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('jenis_pemeriksaan_lab')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Permintaan</label>
                                <textarea name="catatan_lab" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] bg-white @error('catatan_lab') border-red-500 @enderror" placeholder="Catatan untuk laboratorium...">{{ old('catatan_lab') }}</textarea>
                                @error('catatan_lab')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Rujukan (hidden by default) -->
                        <div id="form-rujukan" class="hidden border-2 border-gray-200 rounded-lg p-6 bg-gray-50 space-y-4">
                            <h4 class="font-bold text-gray-800">
                                Form Rujukan
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Rujukan <span class="text-red-500">*</span></label>
                                    <input type="text" name="tujuan_rujukan" value="{{ old('tujuan_rujukan') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] bg-white @error('tujuan_rujukan') border-red-500 @enderror" placeholder="Spesialis Jantung">
                                    @error('tujuan_rujukan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rumah Sakit Tujuan</label>
                                    <input type="text" name="rs_tujuan" value="{{ old('rs_tujuan') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] bg-white @error('rs_tujuan') border-red-500 @enderror" placeholder="RS Hasan Sadikin">
                                    @error('rs_tujuan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Spesialis Tujuan</label>
                                <input type="text" name="dokter_spesialis_tujuan" value="{{ old('dokter_spesialis_tujuan') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] bg-white @error('dokter_spesialis_tujuan') border-red-500 @enderror" placeholder="dr. Nama Dokter, Sp.JP">
                                @error('dokter_spesialis_tujuan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Rujukan <span class="text-red-500">*</span></label>
                                <textarea name="alasan_rujukan" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] bg-white @error('alasan_rujukan') border-red-500 @enderror" placeholder="Alasan merujuk pasien...">{{ old('alasan_rujukan') }}</textarea>
                                @error('alasan_rujukan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnosa Sementara</label>
                                <textarea name="diagnosa_sementara" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] bg-white @error('diagnosa_sementara') border-red-500 @enderror" placeholder="Diagnosa sementara untuk rujukan...">{{ old('diagnosa_sementara') }}</textarea>
                                @error('diagnosa_sementara')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex space-x-4 pt-4">
                        <button type="submit" class="flex-1 bg-[#f56e9d] text-white font-semibold py-3 rounded-lg hover:bg-[#d14a7a] transition-colors">
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
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Riwayat Pemeriksaan (3 Terakhir)</h2>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($riwayatPemeriksaan as $riwayat)
                    <div class="border-2 border-[#f56e9d] rounded-lg p-4">
                        <div class="mb-2">
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($riwayat->tanggal_pemeriksaan)->isoFormat('D MMMM YYYY') }}</p>
                            <p class="text-sm text-gray-600">oleh {{ $riwayat->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? 'Dokter' }}</p>
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

<script>
    // Initialize obatIndex based on existing items
    @if(old('obat'))
    let obatIndex = {{ count(old('obat')) }};
    @else
    let obatIndex = 1;
    @endif

    // Toggle form berdasarkan tindak lanjut yang dipilih
    document.querySelectorAll('input[name="tindak_lanjut"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Hide semua form tindak lanjut
            document.getElementById('form-resep').classList.add('hidden');
            document.getElementById('form-lab').classList.add('hidden');
            document.getElementById('form-rujukan').classList.add('hidden');

            // Show form yang sesuai
            if (this.value === 'resep') {
                document.getElementById('form-resep').classList.remove('hidden');
            } else if (this.value === 'lab') {
                document.getElementById('form-lab').classList.remove('hidden');
            } else if (this.value === 'rujukan') {
                document.getElementById('form-rujukan').classList.remove('hidden');
            }
        });
    });

    // Trigger change on page load untuk restore state
    document.addEventListener('DOMContentLoaded', function() {
        const checked = document.querySelector('input[name="tindak_lanjut"]:checked');
        if (checked) {
            checked.dispatchEvent(new Event('change'));
        }
    });

    // Validasi form sebelum submit
    document.getElementById('form-pemeriksaan').addEventListener('submit', function(e) {
        const tindakLanjut = document.querySelector('input[name="tindak_lanjut"]:checked');

        // Validasi field wajib utama
        const anamnesa = document.querySelector('[name="anamnesa"]');
        const diagnosa = document.querySelector('[name="diagnosa"]');

        let errors = [];

        if (!anamnesa.value.trim()) {
            errors.push('Anamnesa wajib diisi');
            anamnesa.classList.add('border-red-500');
        } else {
            anamnesa.classList.remove('border-red-500');
        }

        if (!diagnosa.value.trim()) {
            errors.push('Diagnosa wajib diisi');
            diagnosa.classList.add('border-red-500');
        } else {
            diagnosa.classList.remove('border-red-500');
        }

        // Validasi berdasarkan tindak lanjut
        if (tindakLanjut && tindakLanjut.value === 'resep') {
            const obatItems = document.querySelectorAll('.obat-item');
            let hasValidObat = false;

            obatItems.forEach(item => {
                const obatId = item.querySelector('[name*="[obat_id]"]');
                const jumlah = item.querySelector('[name*="[jumlah]"]');
                const dosis = item.querySelector('[name*="[dosis]"]');
                const aturan = item.querySelector('[name*="[aturan_pakai]"]');

                if (obatId && obatId.value) {
                    hasValidObat = true;

                    if (!jumlah.value) {
                        errors.push('Jumlah obat wajib diisi');
                        jumlah.classList.add('border-red-500');
                    } else {
                        jumlah.classList.remove('border-red-500');
                    }

                    if (!dosis.value.trim()) {
                        errors.push('Dosis obat wajib diisi');
                        dosis.classList.add('border-red-500');
                    } else {
                        dosis.classList.remove('border-red-500');
                    }

                    if (!aturan.value.trim()) {
                        errors.push('Aturan pakai wajib diisi');
                        aturan.classList.add('border-red-500');
                    } else {
                        aturan.classList.remove('border-red-500');
                    }
                }
            });

            if (!hasValidObat) {
                errors.push('Minimal pilih 1 obat untuk resep');
            }
        } else if (tindakLanjut && tindakLanjut.value === 'lab') {
            const jenisLab = document.querySelector('[name="jenis_pemeriksaan_lab"]');
            if (!jenisLab.value) {
                errors.push('Jenis pemeriksaan lab wajib dipilih');
                jenisLab.classList.add('border-red-500');
            } else {
                jenisLab.classList.remove('border-red-500');
            }
        } else if (tindakLanjut && tindakLanjut.value === 'rujukan') {
            const tujuanRujukan = document.querySelector('[name="tujuan_rujukan"]');
            const alasanRujukan = document.querySelector('[name="alasan_rujukan"]');

            if (!tujuanRujukan.value.trim()) {
                errors.push('Tujuan rujukan wajib diisi');
                tujuanRujukan.classList.add('border-red-500');
            } else {
                tujuanRujukan.classList.remove('border-red-500');
            }

            if (!alasanRujukan.value.trim()) {
                errors.push('Alasan rujukan wajib diisi');
                alasanRujukan.classList.add('border-red-500');
            } else {
                alasanRujukan.classList.remove('border-red-500');
            }
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi:\n\n' + errors.join('\n'));
            // Scroll ke field pertama yang error
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Fungsi untuk menambah obat
    function addObat() {
        const container = document.getElementById('obat-container');
        const newItem = document.createElement('div');
        newItem.className = 'obat-item bg-white rounded-lg p-4 border border-gray-200';
        newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                <select name="obat[${obatIndex}][obat_id]" class="obat-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]">
                    <option value="">Pilih Obat</option>
                    @foreach($obatList as $obat)
                        <option value="{{ $obat->obat_id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="obat[${obatIndex}][jumlah]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]" placeholder="10">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Dosis <span class="text-red-500">*</span></label>
                <input type="text" name="obat[${obatIndex}][dosis]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]" placeholder="500mg">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Aturan Pakai <span class="text-red-500">*</span></label>
                <input type="text" name="obat[${obatIndex}][aturan_pakai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#f56e9d]" placeholder="3x1 sehari">
            </div>
        </div>
        <button type="button" onclick="removeObat(this)" class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium">
            Hapus Obat
        </button>
    `;
        container.appendChild(newItem);
        obatIndex++;
        updateRemoveButtons();
    }

    // Fungsi untuk menghapus obat
    function removeObat(button) {
        const item = button.closest('.obat-item');
        item.remove();
        updateRemoveButtons();
    }

    // Update visibility tombol hapus
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.obat-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('button[onclick="removeObat(this)"]');
            if (removeBtn) {
                if (items.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            }
        });
    }

    // Clear error styling saat user mulai mengetik
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('border-red-500');
            });
            field.addEventListener('change', function() {
                this.classList.remove('border-red-500');
            });
        });
    });
</script>

@if(session('error'))
<x-toast type="error" :message="session('error')" />
@endif

@if(session('success'))
<x-toast type="success" :message="session('success')" />
@endif

@if($errors->any())
<x-toast type="error" message="Terdapat kesalahan pada form. Silakan periksa kembali." />
@endif
@endsection