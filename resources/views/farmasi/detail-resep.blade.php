@extends('layouts.dashboard')

@section('title', 'Detail Resep')
@section('dashboard-title', 'Detail Resep #' . $resep->resep_id)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('farmasi.daftar-resep') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Resep
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Resep -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Pasien & Dokter -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Resep</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Pasien</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $resep->pemeriksaan->pendaftaran->pasien->nama_lengkap ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $resep->pemeriksaan->pendaftaran->pasien->no_rekam_medis ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dokter Peresep</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $resep->pemeriksaan->pendaftaran->jadwalDokter->dokter->nama_lengkap ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $resep->pemeriksaan->pendaftaran->jadwalDokter->dokter->spesialisasi ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Resep</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resep->tanggal_resep->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <p class="mt-1">
                            @if($resep->status == 'menunggu')
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                            @elseif($resep->status == 'diproses')
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Diproses
                            </span>
                            @else
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Daftar Obat -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Obat</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Obat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aturan Pakai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($resep->detailResep as $detail)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $detail->obat->nama_obat }}</div>
                                    <div class="text-xs text-gray-500">{{ $detail->obat->kategori }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $detail->jumlah }} {{ $detail->obat->satuan }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $detail->aturan_pakai }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($detail->obat->stok >= $detail->jumlah)
                                    <span class="text-sm font-medium text-green-600">
                                        {{ $detail->obat->stok }} {{ $detail->obat->satuan }}
                                    </span>
                                    @elseif($detail->obat->stok > 0)
                                    <span class="text-sm font-medium text-orange-600">
                                        {{ $detail->obat->stok }} {{ $detail->obat->satuan }} (Kurang)
                                    </span>
                                    @else
                                    <span class="text-sm font-medium text-red-600">
                                        Stok Habis
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Informasi Apoteker -->
            @if($resep->apoteker)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Diproses Oleh</h3>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-[#f56e9d]/10 rounded-full flex items-center justify-center">
                        <span class="text-[#f56e9d] font-bold text-lg">
                            {{ substr($resep->apoteker->nama_lengkap, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $resep->apoteker->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $resep->apoteker->nip_rs }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>

                @if($resep->status == 'menunggu')
                <form id="prosesResepForm" method="POST" action="{{ route('farmasi.proses-resep', $resep->resep_id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="button" onclick="openProsesModal()" class="w-full px-4 py-3 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition font-medium">
                        Proses Resep
                    </button>
                </form>
                @elseif($resep->status == 'diproses' && $resep->apoteker_id == Auth::user()->staf->staf_id)
                <form id="selesaikanResepForm" method="POST" action="{{ route('farmasi.selesaikan-resep', $resep->resep_id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="button" onclick="openSelesaikanModal()" class="w-full px-4 py-3 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition font-medium">
                        Selesaikan Resep
                    </button>
                </form>
                @endif

                <a href="{{ route('farmasi.daftar-resep') }}" class="mt-3 block w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-center">
                    Kembali
                </a>
            </div>

            <!-- Info Pemeriksaan -->
            @if($resep->pemeriksaan)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Diagnosa</h4>
                <p class="text-sm text-gray-700">{{ $resep->pemeriksaan->diagnosa }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif

<!-- Modal Konfirmasi Proses Resep -->
<div id="prosesModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="prosesModalBackdrop" class="fixed inset-0 bg-black opacity-0 transition-opacity duration-200" onclick="closeProsesModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0 relative z-10" id="prosesModalContent">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Proses Resep</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Yakin ingin memproses resep ini? Status resep akan berubah menjadi "<strong>Diproses</strong>" dan akan ditugaskan kepada Anda.
                    </p>
                </div>
            </div>
        </div>
        <div class="p-6 flex gap-3 justify-end">
            <button
                type="button"
                onclick="closeProsesModal()"
                class="px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Batal
            </button>
            <button
                type="button"
                onclick="confirmProsesResep()"
                class="px-4 py-2 text-sm bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium shadow-sm">
                Ya, Proses
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Selesaikan Resep -->
<div id="selesaikanModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="selesaikanModalBackdrop" class="fixed inset-0 bg-black opacity-0 transition-opacity duration-200" onclick="closeSelesaikanModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0 relative z-10" id="selesaikanModalContent">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Selesaikan Resep</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Yakin ingin menyelesaikan resep ini? <strong>Stok obat akan dikurangi</strong> dan status resep akan berubah menjadi "<strong>Selesai</strong>".
                    </p>
                </div>
            </div>
        </div>
        <div class="p-6 flex gap-3 justify-end">
            <button
                type="button"
                onclick="closeSelesaikanModal()"
                class="px-4 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Batal
            </button>
            <button
                type="button"
                onclick="confirmSelesaikanResep()"
                class="px-4 py-2 text-sm bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium shadow-sm">
                Ya, Selesaikan
            </button>
        </div>
    </div>
</div>

<script>
    // Modal Proses Resep
    function openProsesModal() {
        const modal = document.getElementById('prosesModal');
        const modalContent = document.getElementById('prosesModalContent');
        const modalBackdrop = document.getElementById('prosesModalBackdrop');

        modal.classList.remove('hidden');

        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
            modalBackdrop.classList.remove('opacity-0');
            modalBackdrop.classList.add('opacity-20');
        }, 10);
    }

    function closeProsesModal() {
        const modal = document.getElementById('prosesModal');
        const modalContent = document.getElementById('prosesModalContent');
        const modalBackdrop = document.getElementById('prosesModalBackdrop');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        modalBackdrop.classList.remove('opacity-20');
        modalBackdrop.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function confirmProsesResep() {
        document.getElementById('prosesResepForm').submit();
    }

    // Modal Selesaikan Resep
    function openSelesaikanModal() {
        const modal = document.getElementById('selesaikanModal');
        const modalContent = document.getElementById('selesaikanModalContent');
        const modalBackdrop = document.getElementById('selesaikanModalBackdrop');

        modal.classList.remove('hidden');

        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
            modalBackdrop.classList.remove('opacity-0');
            modalBackdrop.classList.add('opacity-20');
        }, 10);
    }

    function closeSelesaikanModal() {
        const modal = document.getElementById('selesaikanModal');
        const modalContent = document.getElementById('selesaikanModalContent');
        const modalBackdrop = document.getElementById('selesaikanModalBackdrop');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        modalBackdrop.classList.remove('opacity-20');
        modalBackdrop.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function confirmSelesaikanResep() {
        document.getElementById('selesaikanResepForm').submit();
    }
</script>
@endsection