@extends('layouts.dashboard')

@section('title', 'Profil - Staff Pendaftaran')
@section('dashboard-title', 'Profil Saya')

@section('content')
<x-toast type="success" :message="session('success')" />

<div class="w-full min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Profil Saya</h2>
            <a href="{{ route('pendaftaran.profile.edit') }}"
                class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Edit Profil
            </a>
        </div>

        <div class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIP RS</label>
                    <p class="text-gray-900">{{ $staf->nip }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <p class="text-gray-900">{{ $staf->nik }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p class="text-gray-900">{{ $staf->user->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <p class="text-gray-900">{{ $staf->nama_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                    <p class="text-gray-900">{{ $staf->tempat_lahir }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <p class="text-gray-900">{{ $staf->tanggal_lahir->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <p class="text-gray-900">{{ $staf->jenis_kelamin }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                    <p class="text-gray-900">{{ $staf->no_telepon }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                    <p class="text-gray-900 capitalize">{{ ucfirst($staf->bagian) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kewarganegaraan</label>
                    <p class="text-gray-900">{{ $staf->kewarganegaraan ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                    <p class="text-gray-900">{{ $staf->provinsi ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                    <p class="text-gray-900">{{ $staf->kota_kabupaten ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                    <p class="text-gray-900">{{ $staf->kecamatan ?? '-' }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <p class="text-gray-900">{{ $staf->alamat }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
