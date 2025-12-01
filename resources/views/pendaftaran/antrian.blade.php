@extends('layouts.dashboard')

@section('title', 'Antrian Hari Ini')
@section('dashboard-title', 'Antrian Hari Ini')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Antrian Hari Ini</h2>
                <p class="text-sm text-gray-600 mt-1">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Antrian</p>
                <p class="text-3xl font-bold text-[#f56e9d]" id="totalAntrian">{{ $pendaftarans->count() }}</p>
            </div>
        </div>

        <!-- Filter Dokter -->
        <div class="max-w-xs">
            <label for="dokter_id" class="block text-sm font-medium text-gray-700 mb-2">
                Filter Dokter
            </label>
            <select
                name="dokter_id"
                id="dokter_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent"
            >
                <option value="">Semua Dokter</option>
                @foreach($dokters as $dokter)
                <option value="{{ $dokter->dokter_id }}">
                    {{ $dokter->nama_lengkap }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Antrian Table -->
    <div id="antrianContainer" class="bg-white rounded-lg shadow-md overflow-hidden">
        <div id="antrianContent">
            @if($pendaftarans->isEmpty())
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-4 text-gray-600">Tidak ada antrian hari ini</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Antrian
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pasien
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dokter
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Keluhan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendaftarans as $pendaftaran)
                        <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $pendaftaran->pendaftaran_id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#fff5f8]">
                                    <span class="text-xl font-bold text-[#d14a7a]">{{ $pendaftaran->nomor_antrian }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $pendaftaran->pasien->nama_lengkap }}</div>
                                <div class="text-sm text-gray-600">{{ $pendaftaran->pasien->no_rekam_medis }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($pendaftaran->dokter)
                                <div class="text-sm font-medium text-gray-900">{{ $pendaftaran->dokter->nama_lengkap }}</div>
                                @if($pendaftaran->dokter->spesialisasi)
                                <div class="text-xs text-gray-500">{{ $pendaftaran->dokter->spesialisasi }}</div>
                                @endif
                                @else
                                <div class="text-sm text-gray-500">-</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700 max-w-xs">{{ $pendaftaran->keluhan_utama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $pendaftaran->tanggal_daftar->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="status-badge">
                                    @if($pendaftaran->status === 'menunggu')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                    @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Dipanggil
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pendaftaran->status === 'menunggu')
                                <button
                                    type="button"
                                    onclick="panggilPasien({{ $pendaftaran->pendaftaran_id }})"
                                    class="panggil-btn px-4 py-2 bg-[#f56e9d] text-white text-sm rounded-lg hover:bg-[#d14a7a] transition-colors font-medium"
                                >
                                    Panggil
                                </button>
                                @else
                                <button
                                    type="button"
                                    disabled
                                    class="px-4 py-2 bg-gray-300 text-gray-500 text-sm rounded-lg cursor-not-allowed font-medium"
                                >
                                    Sudah Dipanggil
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="modalBackdrop" class="absolute inset-0 bg-black opacity-0 transition-opacity duration-200" onclick="closeConfirmModal()"></div>
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0 relative z-10" id="modalContent">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-[#fff5f8] rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#f56e9d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Panggil Pasien</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Yakin ingin memanggil pasien ini? Pasien akan dipindahkan ke status "Dipanggil".
                    </p>
                </div>
            </div>
        </div>
        <div class="p-6 flex gap-3 justify-end">
            <button
                type="button"
                onclick="closeConfirmModal()"
                class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
            >
                Batal
            </button>
            <button
                type="button"
                onclick="confirmPanggilPasien()"
                class="px-5 py-2.5 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors font-medium shadow-sm"
            >
                Ya, Panggil
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dokterId = document.getElementById('dokter_id');
    let debounceTimer = null;

    function debounce(func, delay) {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        };
    }

    function fetchData() {
        const params = new URLSearchParams({
            dokter_id: dokterId.value
        });

        const url = '{{ route("pendaftaran.antrian") }}?' + params.toString();

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');

                const newContent = doc.getElementById('antrianContent');
                const newTotal = doc.getElementById('totalAntrian');

                if (newContent) {
                    document.getElementById('antrianContent').innerHTML = newContent.innerHTML;
                }

                if (newTotal) {
                    document.getElementById('totalAntrian').textContent = newTotal.textContent;
                }
            }
        };

        xhr.send();
    }

    const debouncedFetch = debounce(() => fetchData(), 500);
    dokterId.addEventListener('change', debouncedFetch);

    // Auto refresh every 30 seconds
    setInterval(fetchData, 30000);
});

let selectedPendaftaranId = null;

function panggilPasien(id) {
    selectedPendaftaranId = id;
    openConfirmModal();
}

function openConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('modalContent');
    const modalBackdrop = document.getElementById('modalBackdrop');

    modal.classList.remove('hidden');

    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
        modalBackdrop.classList.remove('opacity-0');
        modalBackdrop.classList.add('opacity-20');
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('modalContent');
    const modalBackdrop = document.getElementById('modalBackdrop');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    modalBackdrop.classList.remove('opacity-20');
    modalBackdrop.classList.add('opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        selectedPendaftaranId = null;
    }, 200);
}

function confirmPanggilPasien() {
    if (!selectedPendaftaranId) {
        return;
    }

    closeConfirmModal();

    const xhr = new XMLHttpRequest();
    xhr.open('PATCH', `/pendaftaran/${selectedPendaftaranId}/status`, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            showToast('success', response.message);

            // Update UI
            const row = document.querySelector(`[data-id="${selectedPendaftaranId}"]`);
            if (row) {
                const statusBadge = row.querySelector('.status-badge');
                statusBadge.innerHTML = '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Dipanggil</span>';

                const button = row.querySelector('.panggil-btn');
                button.outerHTML = '<button type="button" disabled class="px-4 py-2 bg-gray-300 text-gray-500 text-sm rounded-lg cursor-not-allowed font-medium">Sudah Dipanggil</button>';
            }
        } else {
            const response = JSON.parse(xhr.responseText);
            showToast('error', response.message || 'Terjadi kesalahan');
        }
    };

    xhr.send(JSON.stringify({ status: 'dipanggil' }));
}

function showToast(type, message) {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500'
    };

    const icons = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
    };

    const existingToast = document.getElementById('dynamicToast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.id = 'dynamicToast';
    toast.className = `fixed top-4 right-4 z-50 flex items-center gap-3 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out opacity-0 translate-y-[-20px]`;
    toast.innerHTML = `
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${icons[type]}
        </svg>
        <span class="font-medium">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-y-[-20px]');
    }, 100);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-[-20px]');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}
</script>
@endsection
