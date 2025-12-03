@extends('layouts.dashboard')

@section('title', 'Profil Dokter')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
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

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-8 py-12 text-white">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-pink-500 shadow-inner">
                    {{ strtoupper(substr($dokter->nama_lengkap, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-3xl font-bold">Dr. {{ $dokter->nama_lengkap }}</h2>
                    <p class="text-pink-100 mt-1 text-lg">{{ $dokter->spesialisasi ?? 'Dokter Umum' }}</p>
                    <div class="inline-flex items-center mt-2 bg-pink-700 bg-opacity-30 px-3 py-1 rounded-full text-sm backdrop-blur-sm border border-pink-400">
                        <span>NIP: {{ $dokter->nip }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-pink-500 rounded-full mr-3"></span>
                    Data Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">NIK</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $dokter->nik }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal Lahir</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ \Carbon\Carbon::parse($dokter->tanggal_lahir)->isoFormat('D MMMM YYYY') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Jenis Kelamin</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $dokter->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kewarganegaraan</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $dokter->kewarganegaraan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-pink-500 rounded-full mr-3"></span>
                    Informasi Kontak
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $dokter->no_telepon }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $dokter->user->email }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-pink-500 rounded-full mr-3"></span>
                    Alamat Domisili
                </h3>
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Alamat Lengkap</p>
                        <p class="font-medium text-gray-900">{{ $dokter->alamat }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Provinsi</p>
                            <p class="font-semibold text-gray-900">{{ $dokter->provinsi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Kota/Kabupaten</p>
                            <p class="font-semibold text-gray-900">{{ $dokter->kota_kabupaten ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Kecamatan</p>
                            <p class="font-semibold text-gray-900">{{ $dokter->kecamatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="w-1 h-6 bg-pink-500 rounded-full mr-3"></span>
                        Data Profesional
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Spesialisasi</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                                {{ $dokter->spesialisasi ?? 'Dokter Umum' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nomor STR</p>
                            <p class="font-semibold text-gray-900 tracking-wide">{{ $dokter->no_str ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="w-1 h-6 bg-pink-500 rounded-full mr-3"></span>
                        Jadwal Praktik
                    </h3>
                    
                    @if($dokter->jadwal_praktik)
                        @php
                            // Decode jika tipe datanya masih string JSON, jika sudah array langsung dipakai
                            $jadwal = is_string($dokter->jadwal_praktik) ? json_decode($dokter->jadwal_praktik, true) : $dokter->jadwal_praktik;
                        @endphp

                        @if(!empty($jadwal) && is_array($jadwal))
                            <div class="space-y-3">
                                @foreach($jadwal as $schedule)
                                    <div class="flex justify-between items-center p-3 bg-pink-50 rounded-lg border border-pink-100 hover:shadow-sm transition-shadow">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-pink-100 p-2 rounded-full text-pink-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $schedule['hari'] ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm font-medium text-pink-700 bg-white px-3 py-1 rounded-full shadow-sm">
                                            {{ $schedule['jam_mulai'] ?? '00:00' }} - {{ $schedule['jam_selesai'] ?? '00:00' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <p class="text-gray-500 text-sm">Format jadwal tidak valid</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-500 text-sm">Belum ada jadwal praktik diatur</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif
@endsection