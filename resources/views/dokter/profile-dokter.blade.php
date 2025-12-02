@extends('layouts.dashboard')

@section('title', 'Profil Dokter')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-600 mt-1">Informasi dan data pribadi Anda</p>
        </div>
        <a href="{{ route('dokter.profile.edit') }}" 
           class="px-6 py-3 bg-pink-500 text-white font-semibold rounded-lg hover:bg-pink-600 transition-colors shadow-md">
            Edit Profil
        </a>
    </div>

    <!-- Profil Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header dengan Gradient -->
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-8 py-12 text-white">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-pink-500">
                    {{ strtoupper(substr($dokter->nama_lengkap, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-3xl font-bold">Dr. {{ $dokter->nama_lengkap }}</h2>
                    <p class="text-pink-100 mt-1 text-lg">{{ $dokter->spesialisasi ?? 'Dokter Umum' }}</p>
                    <p class="text-pink-100 text-sm mt-2">NIP: {{ $dokter->nip }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Detail -->
        <div class="p-8 space-y-6">
            <!-- Data Pribadi -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">Data Pribadi</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->nik }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Lahir</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($dokter->tanggal_lahir)->isoFormat('D MMMM YYYY') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jenis Kelamin</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->jenis_kelamin }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kewarganegaraan</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->kewarganegaraan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">Informasi Kontak</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->no_telepon }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">Alamat</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Alamat Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->alamat }}</p>
                    </div>
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Provinsi</p>
                            <p class="font-semibold text-gray-900">{{ $dokter->provinsi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kota/Kabupaten</p>
                            <p class="font-semibold text-gray-900">{{ $dokter->kota_kabupaten ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kecamatan</p>
                            <p class="font-semibold text-gray-900">{{ $dokter->kecamatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Profesional -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">Data Profesional</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Spesialisasi</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->spesialisasi ?? 'Dokter Umum' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. STR</p>
                        <p class="font-semibold text-gray-900">{{ $dokter->no_str ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Jadwal Praktik -->
            @if($dokter->jadwal_praktik)
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-pink-200">Jadwal Praktik</h3>
                    <div class="bg-pink-50 rounded-lg p-4">
                        <pre class="text-gray-700 font-medium whitespace-pre-wrap">{{ json_encode($dokter->jadwal_praktik, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif
@endsection
