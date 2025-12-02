@extends('layouts.dashboard')

@section('title', 'Antrian Pasien')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Antrian Pasien</h1>
        <p class="text-gray-600 mt-1">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
    </div>

    <!-- Antrian Pasien -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($antrianPasien->isEmpty())
            <div class="text-center py-16">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-500 font-medium text-lg">Tidak ada pasien dalam antrian</p>
                <p class="text-gray-400 text-sm mt-2">Semua pasien hari ini sudah ditangani</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-pink-500 to-pink-600 text-white">
                        <tr>
                            <th class="text-left py-4 px-6 font-semibold">No. Antrian</th>
                            <th class="text-left py-4 px-6 font-semibold">Nama Pasien</th>
                            <th class="text-left py-4 px-6 font-semibold">No. RM</th>
                            <th class="text-left py-4 px-6 font-semibold">Keluhan</th>
                            <th class="text-left py-4 px-6 font-semibold">Status</th>
                            <th class="text-center py-4 px-6 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($antrianPasien as $antrian)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-pink-100 text-pink-600 font-bold text-lg">
                                        {{ $antrian->nomor_antrian }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $antrian->pasien->nama_lengkap }}</p>
                                        <p class="text-sm text-gray-500">{{ $antrian->pasien->jenis_kelamin }} • {{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} tahun</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-700 font-medium">{{ $antrian->pasien->no_rm }}</td>
                                <td class="py-4 px-6">
                                    <p class="text-gray-700">{{ Str::limit($antrian->keluhan ?? '-', 50) }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    @if($antrian->status === 'menunggu')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Menunggu
                                        </span>
                                    @elseif($antrian->status === 'dipanggil')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                            </svg>
                                            Dipanggil
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if($antrian->status === 'menunggu')
                                            <form method="POST" action="{{ route('dokter.panggil-pasien', $antrian->pendaftaran_id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium shadow-sm">
                                                    Panggil
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('dokter.form-pemeriksaan', $antrian->pendaftaran_id) }}" 
                                           class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors font-medium shadow-sm">
                                            Periksa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-toast type="error" :message="session('error')" />
@endif
@endsection
