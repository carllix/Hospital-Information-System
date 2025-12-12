@extends('layouts.dashboard')

@section('title', 'Detail Pemeriksaan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dokter.riwayat') }}" class="p-2 bg-white rounded-full text-gray-600 hover:text-pink-600 hover:bg-pink-50 shadow-sm transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pemeriksaan</h1>
                <p class="text-gray-500 text-sm">ID Pemeriksaan: #{{ $pemeriksaan->pemeriksaan_id }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 rounded-full text-sm font-medium
                {{ $pemeriksaan->status_pasien == 'selesai_penanganan' ? 'bg-green-100 text-green-800' : 
                   ($pemeriksaan->status_pasien == 'perlu_resep' ? 'bg-blue-100 text-blue-800' : 
                   ($pemeriksaan->status_pasien == 'dirujuk' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                {{ ucwords(str_replace('_', ' ', $pemeriksaan->status_pasien)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-pink-500 rounded-full mr-3"></span>
                    Data Pasien
                </h3>
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 bg-pink-50 rounded-full flex items-center justify-center text-2xl font-bold text-pink-600">
                        {{-- PERBAIKAN: Ganti $pemeriksaan->pasien menjadi $pemeriksaan->pendaftaran->pasien --}}
                        {{ substr($pemeriksaan->pendaftaran->pasien->nama_lengkap, 0, 1) }}
                    </div>
                    <div>
                        {{-- PERBAIKAN: Ganti $pemeriksaan->pasien menjadi $pemeriksaan->pendaftaran->pasien --}}
                        <h4 class="text-xl font-bold text-gray-900">{{ $pemeriksaan->pendaftaran->pasien->nama_lengkap }}</h4>
                        <p class="text-gray-500 text-sm">No. RM: <span class="font-mono text-gray-700">{{ $pemeriksaan->pendaftaran->pasien->no_rm }}</span></p>
                        <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                            <span>{{ $pemeriksaan->pendaftaran->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            <span>&bull;</span>
                            <span>{{ \Carbon\Carbon::parse($pemeriksaan->pendaftaran->pasien->tanggal_lahir)->age }} Tahun</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-blue-500 rounded-full mr-3"></span>
                    Hasil Pemeriksaan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Keluhan Utama</p>
                        <p class="text-gray-900 font-medium">{{ $pemeriksaan->pendaftaran->keluhan ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Waktu Pemeriksaan</p>
                        <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->isoFormat('dddd, D MMMM YYYY - H:i') }}</p>
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
                        <h4 class="text-sm font-bold text-gray-700 mb-1">Catatan Pemeriksaan / Anamnesa</h4>
                        <div class="p-3 bg-gray-50 rounded text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                            {{-- PERBAIKAN: Ganti catatan_pemeriksaan dengan anamnesa sesuai fillable di model --}}
                            {{ $pemeriksaan->anamnesa ?? 'Tidak ada catatan.' }}
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 mb-1">Diagnosa</h4>
                        <div class="p-3 bg-blue-50 border border-blue-100 rounded text-blue-900 font-medium text-sm">
                            {{ $pemeriksaan->diagnosa }}
                            @if($pemeriksaan->icd10_code)
                                <span class="ml-2 px-2 py-0.5 bg-white rounded text-xs border border-blue-200 text-blue-600">ICD-10: {{ $pemeriksaan->icd10_code }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            
            @if($pemeriksaan->resep)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-green-50 px-4 py-3 border-b border-green-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    <h3 class="font-bold text-green-800">Resep Obat</h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-3">
                        @forelse($pemeriksaan->resep->detailResep as $detail)
                            <li class="flex items-start text-sm border-b border-dashed border-gray-200 pb-2 last:border-0 last:pb-0">
                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    <h3 class="font-bold text-yellow-800">Permintaan Lab</h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-3">
                        @foreach($pemeriksaan->permintaanLab as $lab)
                            <li class="flex items-start text-sm">
                                <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-purple-50 px-4 py-3 border-b border-purple-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="font-bold text-purple-800">Rujukan Keluar</h3>
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
</div>
@endsection