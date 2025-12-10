@extends('layouts.dashboard')

@section('title', 'Detail Staf')
@section('dashboard-title', 'Detail Staf')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Staf</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap staf</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.staf.edit', $staf->staf_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                    Edit Data
                </a>
                <a href="{{ route('admin.staf.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Akun</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">NIP RS</p>
                        <p class="text-base font-semibold text-[#f56e9d]">{{ $staf->nip_rs }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-base font-medium">{{ $staf->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Bagian</p>
                        @php
                        $badgeColor = match($staf->bagian) {
                            'pendaftaran' => 'bg-blue-100 text-blue-800',
                            'farmasi' => 'bg-green-100 text-green-800',
                            'laboratorium' => 'bg-purple-100 text-purple-800',
                            'kasir' => 'bg-yellow-100 text-yellow-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                        @endphp
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $badgeColor }}">
                            {{ ucfirst($staf->bagian) }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Data Pribadi</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="text-base font-medium">{{ $staf->nama_lengkap }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">NIK</p>
                            <p class="text-base font-medium">{{ $staf->nik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jenis Kelamin</p>
                            <p class="text-base font-medium">{{ $staf->jenis_kelamin }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Tempat & Tanggal Lahir</h3>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tempat Lahir</p>
                            <p class="text-base font-medium">{{ $staf->tempat_lahir }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Lahir</p>
                            <p class="text-base font-medium">{{ $staf->tanggal_lahir->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Usia</p>
                        <p class="text-base font-medium">{{ \Carbon\Carbon::parse($staf->tanggal_lahir)->age }} tahun</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Kontak</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="text-base font-medium">{{ $staf->no_telepon }}</p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Alamat</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Alamat Lengkap</p>
                        <p class="text-base font-medium">{{ $staf->alamat }}</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Kecamatan</p>
                            <p class="text-base font-medium">{{ $staf->kecamatan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kota/Kabupaten</p>
                            <p class="text-base font-medium">{{ $staf->kota_kabupaten }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Provinsi</p>
                            <p class="text-base font-medium">{{ $staf->provinsi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
