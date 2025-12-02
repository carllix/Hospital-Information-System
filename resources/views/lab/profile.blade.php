@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('dashboard-title', 'Profil Petugas Lab')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <x-toast :type="session('success') ? 'success' : 'error'" :message="session('success') ?? session('error')" />
    @endif

    {{-- Card Info Utama --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 h-32"></div>
        <div class="px-6 pb-6">
            <div class="flex items-end -mt-16">
                <div class="bg-white rounded-full p-2 shadow-lg">
                    <div class="w-24 h-24 bg-pink-200 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 mb-2">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $petugasLab->nama_lengkap }}</h2>
                    <p class="text-gray-600">Petugas Laboratorium</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi Pribadi --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h3>
            <a href="{{ route('lab.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition-colors text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Profil
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">NIP</label>
                <p class="text-gray-900 font-medium">{{ $petugasLab->nip }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">NIK</label>
                <p class="text-gray-900 font-medium">{{ $petugasLab->nik }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <p class="text-gray-900 font-medium">{{ $petugasLab->nama_lengkap }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                <p class="text-gray-900 font-medium">{{ ucfirst($petugasLab->jenis_kelamin) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                <p class="text-gray-900 font-medium">
                    {{ \Carbon\Carbon::parse($petugasLab->tanggal_lahir)->format('d F Y') }}
                    <span class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($petugasLab->tanggal_lahir)->age }} tahun)</span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">No. Telepon</label>
                <p class="text-gray-900 font-medium">{{ $petugasLab->no_telepon }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Bagian</label>
                <p class="text-gray-900 font-medium">{{ ucwords($petugasLab->bagian) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kewarganegaraan</label>
                <p class="text-gray-900 font-medium">{{ $petugasLab->kewarganegaraan }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                <p class="text-gray-900">{{ $petugasLab->alamat }}</p>
                @if($petugasLab->kecamatan || $petugasLab->kota_kabupaten || $petugasLab->provinsi)
                    <p class="text-gray-600 text-sm mt-1">
                        {{ $petugasLab->kecamatan ? $petugasLab->kecamatan . ', ' : '' }}
                        {{ $petugasLab->kota_kabupaten ? $petugasLab->kota_kabupaten . ', ' : '' }}
                        {{ $petugasLab->provinsi }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Informasi Akun --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Informasi Akun</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <p class="text-gray-900 font-medium">{{ Auth::user()->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Role</label>
                <p class="text-gray-900 font-medium">
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Statistik Kinerja --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Statistik Kinerja</h3>

        @php
            use App\Models\PermintaanLab;
            
            $totalPermintaan = PermintaanLab::where('petugas_lab_id', $petugasLab->staf_id)->count();
            $permintaanBulanIni = PermintaanLab::where('petugas_lab_id', $petugasLab->staf_id)
                ->whereMonth('tanggal_permintaan', now()->month)
                ->whereYear('tanggal_permintaan', now()->year)
                ->count();
            $permintaanMingguIni = PermintaanLab::where('petugas_lab_id', $petugasLab->staf_id)
                ->whereBetween('tanggal_permintaan', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();
            $permintaanSelesai = PermintaanLab::where('petugas_lab_id', $petugasLab->staf_id)
                ->where('status', 'selesai')
                ->count();
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-3xl font-bold text-blue-600">{{ $totalPermintaan }}</p>
                <p class="text-sm text-gray-600 mt-1">Total Pemeriksaan</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-3xl font-bold text-green-600">{{ $permintaanSelesai }}</p>
                <p class="text-sm text-gray-600 mt-1">Selesai</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-3xl font-bold text-purple-600">{{ $permintaanBulanIni }}</p>
                <p class="text-sm text-gray-600 mt-1">Bulan Ini</p>
            </div>
            <div class="text-center p-4 bg-pink-50 rounded-lg">
                <p class="text-3xl font-bold text-pink-600">{{ $permintaanMingguIni }}</p>
                <p class="text-sm text-gray-600 mt-1">Minggu Ini</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('lab.daftar-permintaan') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-900 font-semibold">Daftar Permintaan</p>
                    <p class="text-gray-600 text-sm">Lihat semua permintaan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('lab.riwayat') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-900 font-semibold">Riwayat</p>
                    <p class="text-gray-600 text-sm">Lihat riwayat pemeriksaan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('lab.laporan') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-900 font-semibold">Laporan</p>
                    <p class="text-gray-600 text-sm">Lihat laporan bulanan</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection