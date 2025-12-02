@extends('layouts.dashboard')

@section('title', 'Riwayat Pemeriksaan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Pemeriksaan</h1>
        <p class="text-gray-600 mt-1">Semua pemeriksaan yang telah dilakukan</p>
    </div>

    <!-- Riwayat Pemeriksaan -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($riwayatPemeriksaan->isEmpty())
            <div class="text-center py-16">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium text-lg">Belum ada riwayat pemeriksaan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-pink-500 to-pink-600 text-white">
                        <tr>
                            <th class="text-left py-4 px-6 font-semibold">Tanggal</th>
                            <th class="text-left py-4 px-6 font-semibold">Nama Pasien</th>
                            <th class="text-left py-4 px-6 font-semibold">No. RM</th>
                            <th class="text-left py-4 px-6 font-semibold">Diagnosa</th>
                            <th class="text-left py-4 px-6 font-semibold">Status</th>
                            <th class="text-center py-4 px-6 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($riwayatPemeriksaan as $pemeriksaan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->isoFormat('D MMM YYYY') }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->format('H:i') }}
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $pemeriksaan->pasien->nama_lengkap }}</p>
                                        <p class="text-sm text-gray-500">{{ $pemeriksaan->pasien->jenis_kelamin }} • {{ \Carbon\Carbon::parse($pemeriksaan->pasien->tanggal_lahir)->age }} tahun</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-700 font-medium">
                                    {{ $pemeriksaan->pasien->no_rm }}
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-gray-700">{{ Str::limit($pemeriksaan->diagnosa, 40) }}</p>
                                    @if($pemeriksaan->icd10_code)
                                        <p class="text-xs text-gray-500 mt-1">ICD-10: {{ $pemeriksaan->icd10_code }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($pemeriksaan->status_pasien === 'selesai_penanganan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @elseif($pemeriksaan->status_pasien === 'perlu_resep')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Perlu Resep
                                        </span>
                                    @elseif($pemeriksaan->status_pasien === 'perlu_lab')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Perlu Lab
                                        </span>
                                    @elseif($pemeriksaan->status_pasien === 'dirujuk')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Dirujuk
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('dokter.detail-pemeriksaan', $pemeriksaan->pemeriksaan_id) }}" 
                                           class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors font-medium text-sm">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $riwayatPemeriksaan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
