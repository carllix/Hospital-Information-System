@extends('layouts.dashboard')

@section('title', 'Detail Pemeriksaan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dokter.riwayat') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pemeriksaan</h1>
                <p class="text-gray-500 text-sm">ID Pemeriksaan: #{{ $pemeriksaan->pemeriksaan_id }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 rounded-full text-sm font-medium bg-[#f56e9d]/10 text-[#f56e9d]">
                {{ ucwords(str_replace('_', ' ', $pemeriksaan->status_pasien)) }}
            </span>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                Data Pasien
            </h3>
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-[#f56e9d]/10 rounded-full flex items-center justify-center text-2xl font-bold text-[#f56e9d]">
                    {{-- PERBAIKAN: Ganti $pemeriksaan->pasien menjadi $pemeriksaan->pendaftaran->pasien --}}
                    {{ substr($pemeriksaan->pendaftaran->pasien->nama_lengkap, 0, 1) }}
                </div>
                <div>
                    {{-- PERBAIKAN: Ganti $pemeriksaan->pasien menjadi $pemeriksaan->pendaftaran->pasien --}}
                    <h4 class="text-xl font-bold text-gray-900">{{ $pemeriksaan->pendaftaran->pasien->nama_lengkap }}</h4>
                    <p class="text-gray-500 text-sm">No. RM: <span class="font-mono text-gray-700">{{ $pemeriksaan->pendaftaran->pasien->no_rekam_medis }}</span></p>
                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                        <span>{{ $pemeriksaan->pendaftaran->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span>&bull;</span>
                        <span>{{ \Carbon\Carbon::parse($pemeriksaan->pendaftaran->pasien->tanggal_lahir)->age }} Tahun</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                Hasil Pemeriksaan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Keluhan Utama</p>
                    <p class="text-gray-900 font-medium">{{ $pemeriksaan->pendaftaran->keluhan_utama ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Waktu Pemeriksaan</p>
                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->locale('id')->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB</p>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">Tanda-Tanda Vital</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Tekanan Darah</p>
                        <p class="font-bold text-gray-800">{{ $pemeriksaan->tekanan_darah ?? '-' }} <span class="text-xs font-normal text-gray-500">mmHg</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Suhu Tubuh</p>
                        <p class="font-bold text-gray-800">{{ $pemeriksaan->suhu_tubuh ?? '-' }} <span class="text-xs font-normal text-gray-500">°C</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Berat Badan</p>
                        <p class="font-bold text-gray-800">{{ $pemeriksaan->berat_badan ?? '-' }} <span class="text-xs font-normal text-gray-500">kg</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tinggi Badan</p>
                        <p class="font-bold text-gray-800">{{ $pemeriksaan->tinggi_badan ?? '-' }} <span class="text-xs font-normal text-gray-500">cm</span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-2">Catatan Pemeriksaan / Anamnesa</h4>
                    <div class="p-4 bg-gray-50 rounded text-gray-700 text-sm leading-relaxed">
                        {{-- PERBAIKAN: Ganti catatan_pemeriksaan dengan anamnesa sesuai fillable di model --}}
                        {{ $pemeriksaan->anamnesa ?? 'Tidak ada catatan.' }}
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-2">Diagnosa</h4>
                    <div class="p-4 bg-[#f56e9d]/10 border border-[#f56e9d]/20 rounded text-gray-900 font-medium text-sm">
                        {{ $pemeriksaan->diagnosa }}
                        @if($pemeriksaan->icd10_code)
                        <span class="ml-2 px-2 py-0.5 bg-white rounded text-xs border border-[#f56e9d]/30 text-[#f56e9d]">ICD-10: {{ $pemeriksaan->icd10_code }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($pemeriksaan->resep)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">Resep Obat</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-3">
                    @forelse($pemeriksaan->resep->detailResep as $detail)
                    <li class="flex items-start text-sm border-b border-dashed border-gray-200 pb-2 last:border-0 last:pb-0">
                        <span class="w-1.5 h-1.5 bg-[#f56e9d] rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
                        <div>
                            <p class="font-bold text-gray-800">{{ $detail->obat->nama_obat }}</p>
                            {{-- PERBAIKAN: Ganti 'dosis' dengan 'aturan_pakai' sesuai fillable DetailResep --}}
                            <p class="text-gray-500 text-xs">{{ $detail->jumlah }} {{ $detail->obat->satuan ?? 'item' }} &bull; {{ $detail->aturan_pakai }}</p>
                            @if($detail->keterangan ?? false)
                            <p class="text-gray-400 text-xs italic">"{{ $detail->keterangan }}"</p>
                            @endif
                        </div>
                    </li>
                    @empty
                    <p class="text-gray-400 text-sm text-center italic">Tidak ada detail obat</p>
                    @endforelse
                </ul>
            </div>
        </div>
        @endif

        @if($pemeriksaan->permintaanLab && $pemeriksaan->permintaanLab->count() > 0)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">Permintaan Lab</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-3">
                    @foreach($pemeriksaan->permintaanLab as $lab)
                    <li class="flex items-start text-sm">
                        <span class="w-1.5 h-1.5 bg-[#f56e9d] rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
                        <div>
                            {{-- PERBAIKAN: Ganti 'jenis_pemeriksaan' dengan 'jenis_test' sesuai fillable PermintaanLab --}}
                            <p class="font-medium text-gray-800">{{ $lab->jenis_test ?? 'Cek Lab' }}</p>
                            <p class="text-xs text-gray-500">{{ $lab->catatan_dokter ?? '-' }}</p>
                            <span class="inline-block px-2 py-0.5 text-[10px] rounded bg-gray-100 text-gray-600 mt-1">
                                {{ ucfirst($lab->status ?? 'pending') }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if($pemeriksaan->rujukan)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">Rujukan Keluar</h3>
            </div>
            <div class="p-4 text-sm">
                {{-- PERBAIKAN: Sesuaikan dengan fillable Rujukan (rs_tujuan, bukan rumah_sakit_tujuan) --}}
                <p class="text-gray-500 text-xs">Rumah Sakit Tujuan</p>
                <p class="font-bold text-gray-800 mb-2">{{ $pemeriksaan->rujukan->rs_tujuan }}</p>

                {{-- PERBAIKAN: Gunakan alasan_rujukan karena tidak ada poli_tujuan di fillable --}}
                <p class="text-gray-500 text-xs">Alasan Rujukan</p>
                <p class="font-medium text-gray-800 mb-2">{{ $pemeriksaan->rujukan->alasan_rujukan }}</p>

                @if($pemeriksaan->rujukan->diagnosa_sementara ?? false)
                <p class="text-gray-500 text-xs mt-2">Diagnosa Sementara</p>
                <div class="mt-1 p-2 bg-gray-50 rounded text-gray-600 text-xs">
                    {{ $pemeriksaan->rujukan->diagnosa_sementara }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection