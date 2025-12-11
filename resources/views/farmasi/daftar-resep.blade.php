@extends('layouts.dashboard')

@section('title', 'Daftar Resep')
@section('dashboard-title', 'Daftar Resep')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('farmasi.daftar-resep') }}" class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                @if($status == 'semua')
                    Semua Resep
                @elseif($status == 'menunggu')
                    Resep Menunggu
                @elseif($status == 'diproses')
                    Resep Diproses
                @else
                    Resep Selesai
                @endif
            </h3>
        </div>
        
        @if($resepList->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Resep</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apoteker</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($resepList as $resep)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $resep->resep_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- PERBAIKAN: Akses Pasien via Relasi Panjang --}}
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $resep->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $resep->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? $resep->pemeriksaan->pendaftaran->pasien->no_rm ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{-- PERBAIKAN: Akses Dokter via Relasi Panjang --}}
                                {{ $resep->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $resep->tanggal_resep ? $resep->tanggal_resep->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($resep->status == 'menunggu')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                @elseif($resep->status == 'diproses')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Diproses
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $resep->apoteker ? $resep->apoteker->nama_lengkap : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('farmasi.detail-resep', $resep->resep_id) }}" class="text-pink-600 hover:text-pink-900 font-medium">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $resepList->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada resep</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada resep dengan status ini.</p>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif
@endsection