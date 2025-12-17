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
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Formulir Pemeriksaan</h2>
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

                    <!-- Divider -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tindak Lanjut</h3>

                        <!-- Tindak Lanjut Options -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="selesai" class="peer sr-only" {{ old('tindak_lanjut', 'selesai') == 'selesai' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-gray-300 transition-all">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Selesai</span>
                                    <p class="text-xs text-gray-500 mt-1">Tanpa tindak lanjut</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="resep" class="peer sr-only" {{ old('tindak_lanjut') == 'resep' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Buat Resep</span>
                                    <p class="text-xs text-gray-500 mt-1">Resep obat</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="lab" class="peer sr-only" {{ old('tindak_lanjut') == 'lab' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-gray-300 transition-all">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Permintaan Lab</span>
                                    <p class="text-xs text-gray-500 mt-1">Pemeriksaan lab</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="tindak_lanjut" value="rujukan" class="peer sr-only" {{ old('tindak_lanjut') == 'rujukan' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-gray-300 transition-all">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Buat Rujukan</span>
                                    <p class="text-xs text-gray-500 mt-1">Rujuk ke RS/Spesialis</p>
                                </div>
                            </label>
                        </div>
                        @error('tindak_lanjut')
                            <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                        @enderror

                        <!-- Form Resep (hidden by default) -->
                        <div id="form-resep" class="hidden border-2 border-blue-200 rounded-lg p-6 bg-blue-50 space-y-4">
                            <h4 class="font-bold text-blue-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                                Resep Obat
                            </h4>

                            <div id="obat-container" class="space-y-3">
                                <div class="obat-item bg-white rounded-lg p-4 border border-blue-200">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                                            <select name="obat[0][obat_id]" class="obat-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                                <option value="">Pilih Obat</option>
                                                @foreach($obatList as $obat)
                                                    <option value="{{ $obat->obat_id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah <span class="text-red-500">*</span></label>
                                            <input type="number" name="obat[0][jumlah]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="10">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Dosis <span class="text-red-500">*</span></label>
                                            <input type="text" name="obat[0][dosis]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="500mg">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Aturan Pakai <span class="text-red-500">*</span></label>
                                            <input type="text" name="obat[0][aturan_pakai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="3x1 sehari">
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeObat(this)" class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium hidden">
                                        Hapus Obat
                                    </button>
                                </div>
                            </div>

                            <button type="button" onclick="addObat()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium">
                                + Tambah Obat
                            </button>

                            @error('obat')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Lab (hidden by default) -->
                        <div id="form-lab" class="hidden border-2 border-purple-200 rounded-lg p-6 bg-purple-50 space-y-4">
                            <h4 class="font-bold text-purple-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Permintaan Pemeriksaan Lab
                            </h4>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pemeriksaan <span class="text-red-500">*</span></label>
                                <select name="jenis_pemeriksaan_lab" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white">
                                    <option value="">-- Pilih Jenis Pemeriksaan --</option>
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
                                <textarea name="catatan_lab" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white" placeholder="Catatan untuk laboratorium...">{{ old('catatan_lab') }}</textarea>
                            </div>
                        </div>

                        <!-- Form Rujukan (hidden by default) -->
                        <div id="form-rujukan" class="hidden border-2 border-orange-200 rounded-lg p-6 bg-orange-50 space-y-4">
                            <h4 class="font-bold text-orange-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                Form Rujukan
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Rujukan <span class="text-red-500">*</span></label>
                                    <input type="text" name="tujuan_rujukan" value="{{ old('tujuan_rujukan') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 bg-white" placeholder="Spesialis Jantung">
                                    @error('tujuan_rujukan')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rumah Sakit Tujuan</label>
                                    <input type="text" name="rs_tujuan" value="{{ old('rs_tujuan') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 bg-white" placeholder="RS Hasan Sadikin">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Spesialis Tujuan</label>
                                <input type="text" name="dokter_spesialis_tujuan" value="{{ old('dokter_spesialis_tujuan') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 bg-white" placeholder="dr. Nama Dokter, Sp.JP">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Rujukan <span class="text-red-500">*</span></label>
                                <textarea name="alasan_rujukan" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 bg-white" placeholder="Alasan merujuk pasien...">{{ old('alasan_rujukan') }}</textarea>
                                @error('alasan_rujukan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnosa Sementara</label>
                                <textarea name="diagnosa_sementara" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 bg-white" placeholder="Diagnosa sementara untuk rujukan...">{{ old('diagnosa_sementara') }}</textarea>
                            </div>
                        </div>
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
let obatIndex = 1;

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

// Fungsi untuk menambah obat
function addObat() {
    const container = document.getElementById('obat-container');
    const newItem = document.createElement('div');
    newItem.className = 'obat-item bg-white rounded-lg p-4 border border-blue-200';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                <select name="obat[${obatIndex}][obat_id]" class="obat-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Obat</option>
                    @foreach($obatList as $obat)
                        <option value="{{ $obat->obat_id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="obat[${obatIndex}][jumlah]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="10">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Dosis <span class="text-red-500">*</span></label>
                <input type="text" name="obat[${obatIndex}][dosis]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="500mg">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Aturan Pakai <span class="text-red-500">*</span></label>
                <input type="text" name="obat[${obatIndex}][aturan_pakai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="3x1 sehari">
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
</script>

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection
