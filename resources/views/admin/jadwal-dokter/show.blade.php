@extends('layouts.dashboard')

@section('title', 'Detail Jadwal Dokter')
@section('dashboard-title', 'Detail Jadwal Dokter')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Jadwal Praktik</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap jadwal praktik dokter</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.jadwal-dokter.edit', $jadwal->jadwal_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                    Edit Jadwal
                </a>
                <a href="{{ route('admin.jadwal-dokter.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Dokter</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nama Dokter</p>
                        <p class="text-base font-semibold text-gray-900">{{ $jadwal->dokter->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIP RS</p>
                        <p class="text-base font-medium text-[#f56e9d]">{{ $jadwal->dokter->nip_rs }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Spesialisasi</p>
                        <p class="text-base font-medium">
                            <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                                {{ $jadwal->dokter->spesialisasi }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="text-base font-medium">{{ $jadwal->dokter->no_telepon }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Jadwal</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Hari Praktik</p>
                        <p class="text-base font-medium">
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                {{ $jadwal->hari_praktik }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Waktu Praktik</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Durasi</p>
                        <p class="text-base font-medium">
                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->diffInMinutes(\Carbon\Carbon::parse($jadwal->waktu_selesai)) }} menit
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Maksimal Pasien</p>
                        <p class="text-base font-semibold text-[#f56e9d]">{{ $jadwal->max_pasien }} pasien</p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Tambahan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Dibuat Pada</p>
                        <p class="text-base font-medium">{{ $jadwal->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Terakhir Diupdate</p>
                        <p class="text-base font-medium">{{ $jadwal->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Lihat detail dokter untuk informasi lebih lengkap</span>
                </div>
                <a href="{{ route('admin.dokter.show', $jadwal->dokter_id) }}" class="text-[#f56e9d] hover:text-[#e05d8c] font-medium text-sm">
                    Lihat Detail Dokter →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
