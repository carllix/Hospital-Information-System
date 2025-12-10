@extends('layouts.dashboard')

@section('title', 'Detail Jadwal Dokter')
@section('dashboard-title', 'Detail Jadwal Dokter')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="max-w-6xl mx-auto space-y-6">
    <div class="mb-6">
        <a href="{{ route('admin.jadwal-dokter.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-medium">Kembali ke Daftar Jadwal</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Jadwal Praktik</h2>
            </div>
            <a href="{{ route('admin.jadwal-dokter.edit', $jadwal->jadwal_id) }}" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Edit Jadwal
            </a>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Dokter</label>
                    <p class="text-gray-900">{{ $jadwal->dokter->nama_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NIP RS</label>
                    <p class="text-gray-900">{{ $jadwal->dokter->nip_rs }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Spesialisasi</label>
                    <p class="text-gray-900">{{ $jadwal->dokter->spesialisasi }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. Telepon</label>
                    <p class="text-gray-900">{{ $jadwal->dokter->no_telepon }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hari Praktik</label>
                    <p class="text-gray-900">{{ $jadwal->hari_praktik }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Praktik</label>
                    <p class="text-gray-900">
                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Durasi</label>
                    <p class="text-gray-900">
                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->diffInMinutes(\Carbon\Carbon::parse($jadwal->waktu_selesai)) }} menit
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Maksimal Pasien</label>
                    <p class="text-gray-900">{{ $jadwal->max_pasien }} pasien</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection