@extends('layouts.dashboard')

@section('title', 'Detail Permintaan Lab')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    {{-- Top Navigation --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Detail Pemeriksaan Lab</h1>
            <p class="text-sm text-gray-500 mt-1">ID Permintaan: #LAB-{{ str_pad($permintaan->permintaan_lab_id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('lab.daftar-permintaan') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            
            @if($permintaan->status == 'menunggu')
                <form action="{{ route('lab.ambil-permintaan', $permintaan->permintaan_lab_id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 transition-colors">
                        Ambil Tugas Ini
                    </button>
                </form>
            @elseif($permintaan->status == 'diproses' && $permintaan->petugas_lab_id == Auth::user()->staf->staf_id)
                <a href="{{ route('lab.form-hasil', $permintaan->permintaan_lab_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Input Hasil Lab
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Informasi Pasien & Status (Lebih Kecil) --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Card Pasien --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="h-20 w-20 rounded-full bg-pink-50 flex items-center justify-center text-pink-500 font-bold text-3xl mb-3">
                        {{-- PERBAIKAN: Akses inisial nama pasien --}}
                        {{ substr($permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '?', 0, 1) }}
                    </div>
                    {{-- PERBAIKAN: Akses nama lengkap --}}
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $permintaan->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}
                    </h3>
                    {{-- PERBAIKAN: Akses No RM --}}
                    <p class="text-sm text-gray-500 font-mono">
                        {{ $permintaan->pemeriksaan->pendaftaran->pasien->no_rm ?? $permintaan->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}
                    </p>
                </div>
                
                <div class="border-t border-gray-100 pt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Gender</span>
                        <span class="font-medium text-gray-700">
                            {{-- PERBAIKAN: Akses Gender --}}
                            {{ ($permintaan->pemeriksaan->pendaftaran->pasien->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Umur</span>
                        <span class="font-medium text-gray-700">
                            {{-- PERBAIKAN: Hitung Umur --}}
                            {{ $permintaan->pemeriksaan->pendaftaran->pasien ? \Carbon\Carbon::parse($permintaan->pemeriksaan->pendaftaran->pasien->tanggal_lahir)->age . ' Tahun' : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tanggal Lahir</span>
                        <span class="font-medium text-gray-700">
                            {{-- PERBAIKAN: Tanggal Lahir --}}
                            {{ $permintaan->pemeriksaan->pendaftaran->pasien ? \Carbon\Carbon::parse($permintaan->pemeriksaan->pendaftaran->pasien->tanggal_lahir)->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card Status Permintaan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Permintaan</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status Saat Ini</p>
                        @if($permintaan->status == 'menunggu')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span> Menunggu
                            </span>
                        @elseif($permintaan->status == 'diproses')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2 animate-pulse"></span> Sedang Diproses
                            </span>
                        @elseif($permintaan->status == 'selesai')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Selesai
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jenis Pemeriksaan</p>
                        <p class="font-semibold text-gray-800">{{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Dokter Pengirim</p>
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-600 font-bold">
                                {{-- PERBAIKAN: Inisial Dokter --}}
                                {{ substr($permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? 'D', 0, 1) }}
                            </div>
                            <p class="text-sm font-medium text-gray-700">
                                {{-- PERBAIKAN: Nama Dokter --}}
                                {{ $permintaan->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? 'Dokter Tidak Ditemukan' }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Waktu Permintaan</p>
                        <p class="text-sm text-gray-700">{{ $permintaan->tanggal_permintaan ? $permintaan->tanggal_permintaan->format('d M Y, H:i') : '-' }}</p>
                    </div>

                    @if($permintaan->catatan_permintaan)
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Catatan</p>
                        <p class="text-sm text-gray-600 italic">"{{ $permintaan->catatan_permintaan }}"</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Hasil Lab (Lebih Besar) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Hasil Analisa</h3>
                    @if($permintaan->hasilLab->isNotEmpty() && $permintaan->hasilLab->first()->created_at)
                        <span class="text-xs text-gray-500">Terakhir update: {{ \Carbon\Carbon::parse($permintaan->hasilLab->first()->created_at)->format('d/m/Y') }}</span>
                    @endif
                </div>

                @if($permintaan->hasilLab->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Parameter</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hasil</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rujukan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ket</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($permintaan->hasilLab as $hasil)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $hasil->parameter }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-800">{{ $hasil->hasil }}</span>
                                        <span class="text-xs text-gray-500 ml-1">{{ $hasil->satuan }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $hasil->nilai_normal ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $hasil->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer Hasil (Petugas & Download) --}}
                    <div class="p-6 bg-gray-50 border-t border-gray-100">
                        @php $hasilPertama = $permintaan->hasilLab->first(); @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="text-sm">
                                    <p class="text-gray-500 text-xs">Diperiksa oleh:</p>
                                    <p class="font-semibold text-gray-900">{{ $hasilPertama->petugasLab->nama_lengkap ?? 'Petugas' }}</p>
                                    <p class="text-xs text-gray-400">Petugas Lab</p>
                                </div>
                            </div>

                            @if($hasilPertama->file_hasil_url)
                                <div class="w-full">
                                    @php
                                        $fileExtension = pathinfo($hasilPertama->file_hasil_url, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']);
                                    @endphp

                                    @if($isImage)
                                        {{-- Preview Gambar --}}
                                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                                            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                                <p class="text-sm font-semibold text-gray-700">Lampiran Hasil Lab (Gambar)</p>
                                            </div>
                                            <div class="p-4">
                                                <img src="{{ asset($hasilPertama->file_hasil_url) }}"
                                                     alt="Hasil Lab"
                                                     class="w-full rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity"
                                                     onclick="window.open('{{ asset($hasilPertama->file_hasil_url) }}', '_blank')">
                                                <p class="text-xs text-gray-500 mt-2 text-center">Klik gambar untuk melihat ukuran penuh</p>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Link Download untuk PDF --}}
                                        <a href="{{ asset($hasilPertama->file_hasil_url) }}" target="_blank" class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl hover:border-pink-300 hover:shadow-sm transition-all group">
                                            <div class="bg-red-50 p-2 rounded-lg text-red-500 group-hover:bg-red-100 transition-colors">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-sm font-semibold text-gray-700 group-hover:text-pink-600 transition-colors">Lampiran Hasil Lab (PDF)</p>
                                                <p class="text-xs text-gray-400">Klik untuk melihat file asli</p>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="py-12 flex flex-col items-center justify-center text-center px-4">
                        <div class="bg-gray-100 p-4 rounded-full mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <p class="text-gray-500 font-medium">Hasil pemeriksaan belum diinput.</p>
                        @if($permintaan->status == 'diproses' && $permintaan->petugas_lab_id == Auth::user()->staf->staf_id)
                            <a href="{{ route('lab.form-hasil', $permintaan->permintaan_lab_id) }}" class="mt-3 text-pink-600 hover:text-pink-700 text-sm font-medium hover:underline">
                                Input hasil sekarang &rarr;
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection