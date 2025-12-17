@extends('layouts.dashboard')

@section('title', 'Detail Staf | Admin Ganesha Hospital')
@section('dashboard-title', 'Detail Staf')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.staf.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-medium">Kembali ke Daftar Staf</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Profil Staf</h2>
            </div>
            <a href="{{ route('admin.staf.edit', $staf->staf_id) }}" class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors">
                Edit Profil
            </a>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NIP RS</label>
                    <p class="text-gray-900">{{ $staf->nip_rs }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <p class="text-gray-900">{{ $staf->user->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NIK</label>
                    <p class="text-gray-900">{{ $staf->nik }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <p class="text-gray-900">{{ $staf->nama_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir</label>
                    <p class="text-gray-900">{{ $staf->tempat_lahir }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir</label>
                    <p class="text-gray-900">{{ $staf->tanggal_lahir->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin</label>
                    <p class="text-gray-900">{{ $staf->jenis_kelamin }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No Telepon</label>
                    <p class="text-gray-900">{{ $staf->no_telepon }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Bagian</label>
                    <p class="text-gray-900">{{ ucfirst($staf->bagian) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat</label>
                    <p class="text-gray-900">{{ $staf->alamat }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                    <p class="text-gray-900">{{ $staf->provinsi }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota/Kabupaten</label>
                    <p class="text-gray-900">{{ $staf->kota_kabupaten }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                    <p class="text-gray-900">{{ $staf->kecamatan }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection