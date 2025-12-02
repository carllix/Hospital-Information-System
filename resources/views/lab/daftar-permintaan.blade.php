@extends('layouts.dashboard')

@section('title', 'Daftar Permintaan Lab')
@section('dashboard-title', 'Daftar Permintaan Lab')

@section('content')
<div class="space-y-6">
    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <x-toast :type="session('success') ? 'success' : 'error'" :message="session('success') ?? session('error')" />
    @endif

    {{-- Filter Status --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('lab.daftar-permintaan') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                    <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded-md hover:bg-pink-700 transition-colors">
                Filter
            </button>
        </form>
    </div>

    {{-- Daftar Permintaan --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                @if($status == 'semua')
                    Semua Permintaan Lab
                @else
                    Permintaan Lab - {{ ucfirst($status) }}
                @endif
                ({{ $permintaanList->total() }})
            </h3>
        </div>

        @if($permintaanList->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter Pengirim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pemeriksaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas Lab</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permintaanList as $permintaan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $permintaan->pasien->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">RM: {{ $permintaan->pasien->no_rekam_medis }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $permintaan->dokter->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $permintaan->dokter->spesialisasi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucwords(str_replace('_', ' ', $permintaan->jenis_pemeriksaan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $permintaan->tanggal_permintaan->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $permintaan->petugasLab ? $permintaan->petugasLab->nama_lengkap : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($permintaan->status == 'menunggu')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                @elseif($permintaan->status == 'diproses')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Sedang Diproses
                                    </span>
                                @elseif($permintaan->status == 'selesai')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('lab.detail-permintaan', $permintaan->permintaan_lab_id) }}" class="text-blue-600 hover:text-blue-900">
                                    Detail
                                </a>
                                @if($permintaan->status == 'menunggu')
                                    <form action="{{ route('lab.ambil-permintaan', $permintaan->permintaan_lab_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-pink-600 hover:text-pink-900">
                                            Ambil
                                        </button>
                                    </form>
                                @elseif($permintaan->status == 'diproses' && $permintaan->petugas_lab_id == Auth::user()->staf->staf_id)
                                    <a href="{{ route('lab.form-hasil', $permintaan->permintaan_lab_id) }}" class="text-green-600 hover:text-green-900">
                                        Input Hasil
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $permintaanList->links() }}
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2">Tidak ada permintaan lab</p>
            </div>
        @endif
    </div>
</div>
@endsection
