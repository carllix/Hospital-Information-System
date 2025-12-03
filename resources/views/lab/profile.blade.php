@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Header Background --}}
    <div class="relative mb-20">
        <div class="h-48 w-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 rounded-3xl shadow-lg overflow-hidden relative">
            {{-- Decorative Circles --}}
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-10"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-40 h-40 rounded-full bg-white opacity-10"></div>
        </div>
        
        {{-- Floating Profile Card --}}
        <div class="absolute -bottom-16 left-8 flex items-end">
            <div class="p-1.5 bg-white rounded-full shadow-xl">
                <div class="h-32 w-32 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 border-4 border-white overflow-hidden">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="mb-4 ml-4">
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $petugasLab->nama_lengkap }}</h1>
                <div class="flex items-center gap-2 text-gray-600 font-medium">
                    <span class="bg-white/80 backdrop-blur-sm px-3 py-1 rounded-full text-sm border border-gray-200 shadow-sm">
                        {{ ucwords($petugasLab->bagian) }}
                    </span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm border border-green-200 shadow-sm flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Aktif
                    </span>
                </div>
            </div>
        </div>

        {{-- Edit Button (Top Right) --}}
        <div class="absolute bottom-4 right-8">
            <a href="{{ route('lab.profile.edit') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:text-pink-600 shadow-lg transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Profil
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Statistik & Akun --}}
        <div class="space-y-6">
            {{-- Statistik Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Kinerja Saya
                </h3>
                
                @php
                    use App\Models\PermintaanLab;
                    $totalPermintaan = PermintaanLab::where('petugas_lab_id', $petugasLab->staf_id)->count();
                    $permintaanSelesai = PermintaanLab::where('petugas_lab_id', $petugasLab->staf_id)->where('status', 'selesai')->count();
                    // Hitung persentase (dummy logic jika total 0)
                    $persenSelesai = $totalPermintaan > 0 ? round(($permintaanSelesai / $totalPermintaan) * 100) : 0;
                @endphp

                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                        <p class="text-sm text-blue-600 font-medium">Total Ditangani</p>
                        <p class="text-3xl font-bold text-blue-800">{{ $totalPermintaan }}</p>
                        <p class="text-xs text-blue-500 mt-1">Pasien diperiksa</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-gray-600">Tingkat Penyelesaian</span>
                            <span class="text-green-600">{{ $persenSelesai }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $persenSelesai }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Menu --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">Akses Cepat</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <a href="{{ route('lab.daftar-permintaan') }}" class="flex items-center px-4 py-3 hover:bg-pink-50 group transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Daftar Permintaan</p>
                        </div>
                        <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <a href="{{ route('lab.laporan') }}" class="flex items-center px-4 py-3 hover:bg-pink-50 group transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Laporan Bulanan</p>
                        </div>
                        <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Informasi --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b border-gray-100">Informasi Pribadi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div class="group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">NIP (Nomor Induk Pegawai)</label>
                        <p class="text-gray-900 font-medium text-base group-hover:text-pink-600 transition-colors">{{ $petugasLab->nip }}</p>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">NIK</label>
                        <p class="text-gray-900 font-medium text-base group-hover:text-pink-600 transition-colors">{{ $petugasLab->nik }}</p>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-gray-900 font-medium text-base">
                                {{ \Carbon\Carbon::parse($petugasLab->tanggal_lahir)->format('d F Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                        <p class="text-gray-900 font-medium text-base">{{ ucfirst($petugasLab->jenis_kelamin) }}</p>
                    </div>

                    <div class="group md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <p class="text-gray-900 font-medium">{{ $petugasLab->alamat }}</p>
                                <p class="text-gray-500 text-sm mt-0.5">
                                    {{ $petugasLab->kecamatan }}, {{ $petugasLab->kota_kabupaten }}, {{ $petugasLab->provinsi }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-bold text-gray-900 mb-4">Informasi Kontak & Akun</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-white p-2 rounded-full shadow-sm text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Telepon</p>
                                <p class="font-medium text-gray-900">{{ $petugasLab->no_telepon }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-white p-2 rounded-full shadow-sm text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email Akun</p>
                                <p class="font-medium text-gray-900">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection