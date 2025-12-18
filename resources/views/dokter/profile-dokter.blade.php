@extends('layouts.dashboard')

@section('title', 'Profil Dokter')

@section('content')
<x-toast type="success" :message="session('success')" />

<div class="w-full min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Profil Saya</h2>
            <a href="{{ route('dokter.profile.edit') }}"
                class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Edit Profil
            </a>
        </div>

        <div class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <p class="text-gray-900">{{ $dokter->nik }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIP RS</label>
                    <p class="text-gray-900">{{ $dokter->nip_rs }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p class="text-gray-900">{{ $dokter->user->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <p class="text-gray-900">{{ $dokter->nama_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                    <p class="text-gray-900">{{ $dokter->tempat_lahir }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($dokter->tanggal_lahir)->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <p class="text-gray-900">{{ $dokter->jenis_kelamin }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                    <p class="text-gray-900">{{ $dokter->no_telepon }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spesialisasi</label>
                    <p class="text-gray-900">{{ $dokter->spesialisasi }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor STR</label>
                    <p class="text-gray-900">{{ $dokter->no_str }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                    <p class="text-gray-900">{{ $dokter->provinsi ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                    <p class="text-gray-900">{{ $dokter->kota_kabupaten ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                    <p class="text-gray-900">{{ $dokter->kecamatan ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <p class="text-gray-900">{{ $dokter->alamat }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Praktik</h3>
                @if($dokter->jadwalDokter && $dokter->jadwalDokter->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($dokter->jadwalDokter as $jadwal)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#f56e9d] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $jadwal->hari_praktik }}</p>
                                    <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Jadwal praktik belum tersedia</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection