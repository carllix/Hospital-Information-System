@extends('layouts.dashboard')

@section('title', 'Detail Permintaan Lab')
@section('dashboard-title', 'Detail Permintaan Lab')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Informasi Pasien & Dokter --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pasien</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm text-gray-600">Nama Pasien</label>
                <p class="font-medium text-gray-900">{{ $permintaan->pasien->nama_lengkap }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">No. Rekam Medis</label>
                <p class="font-medium text-gray-900">{{ $permintaan->pasien->no_rekam_medis }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Jenis Kelamin / Umur</label>
                <p class="font-medium text-gray-900">
                    {{ ucfirst($permintaan->pasien->jenis_kelamin) }} / {{ \Carbon\Carbon::parse($permintaan->pasien->tanggal_lahir)->age }} tahun
                </p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Dokter Pengirim</label>
                <p class="font-medium text-gray-900">{{ $permintaan->dokter->nama_lengkap }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Spesialisasi</label>
                <p class="font-medium text-gray-900">{{ $permintaan->dokter->spesialisasi }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Tanggal Permintaan</label>
                <p class="font-medium text-gray-900">{{ $permintaan->tanggal_permintaan->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Detail Permintaan --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Detail Permintaan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-600">Jenis Pemeriksaan</label>
                <p class="font-medium text-pink-600">{{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Status</label>
                <p>
                    @if($permintaan->status == 'menunggu')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu
                        </span>
                    @elseif($permintaan->status == 'diproses')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Sedang Diproses
                        </span>
                    @elseif($permintaan->status == 'selesai')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Selesai
                        </span>
                    @endif
                </p>
            </div>
            @if($permintaan->petugasLab)
            <div>
                <label class="text-sm text-gray-600">Petugas Lab</label>
                <p class="font-medium text-gray-900">{{ $permintaan->petugasLab->nama_lengkap }}</p>
            </div>
            @endif
        </div>

        @if($permintaan->catatan_permintaan)
        <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400">
            <p class="text-sm font-medium text-yellow-800">Catatan Dokter:</p>
            <p class="text-sm text-yellow-700 mt-1">{{ $permintaan->catatan_permintaan }}</p>
        </div>
        @endif
    </div>

    {{-- Hasil Lab --}}
    @if($permintaan->hasilLab->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Hasil Pemeriksaan Lab</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Normal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($permintaan->hasilLab as $hasil)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $hasil->parameter }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                            {{ $hasil->hasil }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $hasil->satuan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $hasil->nilai_normal ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $hasil->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $hasilPertama = $permintaan->hasilLab->first();
        @endphp

        <div class="mt-6 pt-6 border-t border-gray-200 text-sm text-gray-600">
            <p><strong>Tanggal Hasil:</strong> {{ $hasilPertama->tanggal_hasil->format('d/m/Y H:i') }}</p>
            <p><strong>Petugas Lab:</strong> {{ $hasilPertama->petugasLab->nama_lengkap }}</p>
            @if($hasilPertama->file_hasil_url)
            <p class="mt-2">
                <a href="{{ asset($hasilPertama->file_hasil_url) }}" target="_blank" class="inline-flex items-center text-pink-600 hover:text-pink-800 underline">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download File Hasil
                </a>
            </p>
            @endif
        </div>
    </div>
    @endif

    {{-- Tombol Aksi --}}
    <div class="flex justify-between">
        <a href="{{ route('lab.daftar-permintaan') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
            ← Kembali
        </a>
        
        @if($permintaan->status == 'menunggu')
        <form action="{{ route('lab.ambil-permintaan', $permintaan->permintaan_lab_id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition-colors">
                Ambil Permintaan
            </button>
        </form>
        @elseif($permintaan->status == 'diproses' && $permintaan->petugas_lab_id == Auth::user()->staf->staf_id)
        <a href="{{ route('lab.form-hasil', $permintaan->permintaan_lab_id) }}" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
            Input Hasil Lab
        </a>
        @endif
    </div>
</div>
@endsection