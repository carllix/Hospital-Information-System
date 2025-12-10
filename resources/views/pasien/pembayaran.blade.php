@extends('layouts.dashboard')

@section('title', 'Pembayaran | Ganesha Hospital')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="max-w-7xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Tagihan & Pembayaran</h2>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Tagihan Belum Bayar</p>
                <p class="text-3xl font-bold text-[#f56e9d]">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Pembayaran
                    </label>
                    <select
                        id="status_filter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]"
                    >
                        <option value="">Semua Status</option>
                        <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="sebagian" {{ request('status') === 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                        <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="tagihanContainer">
            <div id="tagihanContent">
        @if($tagihans->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Tidak ada tagihan</p>
        </div>
        @else
        <div class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Pendaftaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Tagihan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tagihans as $tagihan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $tagihan->created_at->translatedFormat('j F Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $tagihan->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $tagihan->pemeriksaan->pendaftaran->nomor_antrian ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold">
                                    Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tagihan->status === 'lunas')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">
                                    Lunas
                                </span>
                                @elseif($tagihan->status === 'sebagian')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-yellow-100 text-yellow-800">
                                    Sebagian
                                </span>
                                @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-red-100 text-red-800">
                                    Belum Bayar
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openDetailTagihan({{ $tagihan->tagihan_id }})"
                                    class="px-3 py-2 text-xs bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors duration-200 cursor-pointer">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-700">
                    Menampilkan
                    <span class="font-medium">{{ $tagihans->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="font-medium">{{ $tagihans->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-medium">{{ $tagihans->total() }}</span>
                    data
                </p>
                @if($tagihans->hasPages())
                <div>
                    {{ $tagihans->links() }}
                </div>
                @endif
            </div>
        </div>
        @endif
            </div>
        </div>
    </div>
</div>

<div id="detailTagihanModal" class="hidden fixed inset-0 z-50">
    <div class="fixed inset-0 bg-black" style="opacity: 0.2;"></div>
    <div class="fixed inset-0 flex items-center justify-center pl-64 overflow-y-auto">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto m-4">
            <div id="detailTagihanContent" class="p-6"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status_filter');
    let debounceTimer = null;

    function debounce(func, delay) {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, delay);
        };
    }

    function fetchData() {
        const params = new URLSearchParams({
            status: statusFilter.value
        });

        const url = '{{ route("pasien.pembayaran") }}?' + params.toString();

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');

                const newContent = doc.getElementById('tagihanContent');
                const newTotal = doc.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]');

                if (newContent) {
                    document.getElementById('tagihanContent').innerHTML = newContent.innerHTML;
                }

                if (newTotal) {
                    document.querySelector('.text-3xl.font-bold.text-\\[\\#f56e9d\\]').textContent = newTotal.textContent;
                }

                // Update URL without reload
                window.history.pushState({}, '', url);
            }
        };

        xhr.send();
    }

    const debouncedFetch = debounce(() => fetchData(), 500);

    statusFilter.addEventListener('change', debouncedFetch);
});

window.openDetailTagihan = function(tagihanId) {
    const modal = document.getElementById('detailTagihanModal');
    const modalContent = document.getElementById('detailTagihanContent');

    document.body.style.overflow = 'hidden';
    modal.classList.remove('hidden');
    modalContent.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#f56e9d]"></div>
        </div>
    `;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/pasien/pembayaran/' + tagihanId, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                renderDetailTagihan(response.data);
            } else {
                modalContent.innerHTML = '<div class="text-red-600 text-center py-8">Gagal memuat data</div>';
            }
        } else {
            modalContent.innerHTML = '<div class="text-red-600 text-center py-8">Terjadi kesalahan</div>';
        }
    };

    xhr.onerror = function() {
        modalContent.innerHTML = '<div class="text-red-600 text-center py-8">Terjadi kesalahan jaringan</div>';
    };

    xhr.send();
};

function renderDetailTagihan(data) {
    const modalContent = document.getElementById('detailTagihanContent');

    let statusBadge;
    if (data.status === 'lunas') {
        statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>';
    } else if (data.status === 'sebagian') {
        statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sebagian</span>';
    } else {
        statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Belum Bayar</span>';
    }

    let html = `
        <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-200">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Detail Tagihan</h3>
            </div>
        </div>

        <div class="border border-gray-400 rounded-xl p-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Tanggal Tagihan</p>
                    <p class="text-base font-semibold text-gray-900">${data.tanggal}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-base">${statusBadge}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">No. Pendaftaran</p>
                    <p class="text-base font-semibold text-gray-900">${data.nomor_antrian || '-'}</p>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Detail Item</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
    `;

    if (data.detail_items && data.detail_items.length > 0) {
        data.detail_items.forEach(item => {
            html += `
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs rounded-full ${
                            item.jenis_item === 'Konsultasi' ? 'bg-blue-100 text-blue-800' :
                            item.jenis_item === 'Tindakan' ? 'bg-purple-100 text-purple-800' :
                            item.jenis_item === 'Obat' ? 'bg-green-100 text-green-800' :
                            'bg-orange-100 text-orange-800'
                        }">${item.jenis_item}</span>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-900">${item.nama_item}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">${item.jumlah}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">Rp ${item.harga_satuan}</td>
                    <td class="px-4 py-2 text-sm font-semibold text-gray-900">Rp ${item.subtotal}</td>
                </tr>
            `;
        });
    } else {
        html += `
            <tr>
                <td colspan="5" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada detail item</td>
            </tr>
        `;
    }

    html += `
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <p class="text-lg font-bold text-gray-900">
                    Total: <span class="text-[#f56e9d]">Rp ${data.total_tagihan}</span>
                </p>
            </div>
        </div>
    `;

    if (data.status === 'lunas' && data.pembayaran) {
        html += `
            <div class="mb-6 border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Informasi Pembayaran</h4>
                <div class="border border-gray-400  rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Tanggal Bayar</p>
                            <p class="font-medium text-gray-900">${data.pembayaran.tanggal_bayar}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Metode Pembayaran</p>
                            <p class="font-medium text-gray-900">${data.pembayaran.metode_pembayaran}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">No. Kwitansi</p>
                            <p class="font-medium text-gray-900">${data.pembayaran.no_kwitansi}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Jumlah Bayar</p>
                            <p class="font-bold text-[#f56e9d]">Rp ${data.pembayaran.jumlah_bayar}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    html += `
        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeDetailTagihan()"
                class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] hover:cursor-pointer transition-all duration-200 shadow-md hover:shadow-lg">
                Tutup
            </button>
        </div>
    `;

    modalContent.innerHTML = html;
}

function closeDetailTagihan() {
    const modal = document.getElementById('detailTagihanModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';

    const modalContainer = modal.querySelector('.max-h-\\[90vh\\]');
    if (modalContainer) {
        modalContainer.scrollTop = 0;
    }
}

document.getElementById('detailTagihanModal').addEventListener('click', function(e) {
    if (e.target === this || e.target.style.opacity === '0.2' ||
        (e.target.classList.contains('flex') && e.target.classList.contains('pl-64'))) {
        closeDetailTagihan();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('detailTagihanModal');
        if (!modal.classList.contains('hidden')) {
            closeDetailTagihan();
        }
    }
});
</script>
@endsection
