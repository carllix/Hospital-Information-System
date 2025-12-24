@extends('layouts.dashboard')

@section('title', 'Rekam Medis | Ganesha Hospital')
@section('dashboard-title', 'Rekam Medis')

@section('content')
<div class="w-full min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Rekam Medis</h2>

        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Diagnosa atau dokter..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                </div>

                <div>
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                </div>

                <div>
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d]">
                        <option value="">Semua Status</option>
                        <option value="dalam_pemeriksaan" {{ request('status') == 'dalam_pemeriksaan' ? 'selected' : '' }}>Dalam Pemeriksaan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="loadingIndicator" class="hidden mb-4">
            <div class="flex items-center justify-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#f56e9d]"></div>
                <span class="ml-3 text-gray-600">Memuat data...</span>
            </div>
        </div>

        <div id="tableContainer" data-url="{{ route('pasien.rekam-medis') }}">
            @include('pasien.components.rekam-medis-table', ['pemeriksaan' => $pemeriksaan])
        </div>
    </div>
</div>

<div id="detailModal" class="hidden fixed inset-0 z-50">
    <div class="fixed inset-0 bg-black" style="opacity: 0.2;"></div>

    <div class="fixed inset-0 flex items-center justify-center pl-64 overflow-y-auto">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4">
            <div id="modalContent" class="p-6"></div>
        </div>
    </div>
</div>

<script>
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const searchInput = document.getElementById('search');
    const tanggalDariInput = document.getElementById('tanggal_dari');
    const tanggalSampaiInput = document.getElementById('tanggal_sampai');
    const statusSelect = document.getElementById('status');
    const tableContainer = document.getElementById('tableContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');

    const rekamMedisUrl = document.getElementById('tableContainer').dataset.url;

    function fetchData() {
        loadingIndicator.classList.remove('hidden');
        tableContainer.style.opacity = '0.5';

        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (tanggalDariInput.value) params.append('tanggal_dari', tanggalDariInput.value);
        if (tanggalSampaiInput.value) params.append('tanggal_sampai', tanggalSampaiInput.value);
        if (statusSelect.value) params.append('status', statusSelect.value);

        const xhr = new XMLHttpRequest();
        const url = `${rekamMedisUrl}?${params.toString()}`;

        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'text/html');

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                tableContainer.innerHTML = xhr.responseText;

                loadingIndicator.classList.add('hidden');
                tableContainer.style.opacity = '1';

                const newUrl = `${rekamMedisUrl}?${params.toString()}`;
                window.history.pushState({}, '', newUrl);

                attachPaginationHandlers();
            } else {
                console.error('Request failed with status:', xhr.status);
                loadingIndicator.classList.add('hidden');
                tableContainer.style.opacity = '1';
            }
        };

        xhr.onerror = function() {
            console.error('Request failed');
            loadingIndicator.classList.add('hidden');
            tableContainer.style.opacity = '1';
        };

        xhr.send();
    }

    function attachPaginationHandlers() {
        const paginationLinks = document.querySelectorAll('#paginationContainer a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href;

                loadingIndicator.classList.remove('hidden');
                tableContainer.style.opacity = '0.5';

                const xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'text/html');

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        tableContainer.innerHTML = xhr.responseText;
                        loadingIndicator.classList.add('hidden');
                        tableContainer.style.opacity = '1';
                        window.history.pushState({}, '', url);

                        tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });

                        attachPaginationHandlers();
                    } else {
                        console.error('Request failed with status:', xhr.status);
                        loadingIndicator.classList.add('hidden');
                        tableContainer.style.opacity = '1';
                    }
                };

                xhr.onerror = function() {
                    console.error('Request failed');
                    loadingIndicator.classList.add('hidden');
                    tableContainer.style.opacity = '1';
                };

                xhr.send();
            });
        });
    }

    const debouncedFetch = debounce(fetchData, 500);

    searchInput.addEventListener('input', debouncedFetch);
    tanggalDariInput.addEventListener('change', debouncedFetch);
    tanggalSampaiInput.addEventListener('change', debouncedFetch);

    statusSelect.addEventListener('change', fetchData);

    document.addEventListener('DOMContentLoaded', () => {
        attachPaginationHandlers();
    });

    window.openDetailModal = function(pemeriksaanId) {
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');

        document.body.style.overflow = 'hidden';

        modal.classList.remove('hidden');
        modalContent.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#f56e9d]"></div>
            </div>
        `;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/pasien/rekam-medis/' + pemeriksaanId, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    renderModalContent(response.data);
                } else {
                    modalContent.innerHTML = '<div class="text-red-600 text-center py-8">Gagal memuat data</div>';
                }
            } else {
                modalContent.innerHTML = '<div class="text-red-600 text-center py-8">Terjadi kesalahan saat memuat data</div>';
            }
        };

        xhr.onerror = function() {
            modalContent.innerHTML = '<div class="text-red-600 text-center py-8">Terjadi kesalahan jaringan</div>';
        };

        xhr.send();
    };

    function renderModalContent(data) {
        const modalContent = document.getElementById('modalContent');

        let statusBadge = '';
        switch(data.status) {
            case 'selesai':
                statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">Selesai</span>';
                break;
            case 'dalam_pemeriksaan':
                statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-blue-100 text-blue-800">Dalam Pemeriksaan</span>';
                break;
        }

        let html = `
            <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-200">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Detail Rekam Medis</h3>
                </div>
            </div>

            <!-- Informasi Umum -->
            <div class="border border-gray-400 rounded-xl p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pemeriksaan</p>
                        <p class="text-base font-semibold text-gray-900">${data.tanggal_pemeriksaan}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-base">${statusBadge}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dokter</p>
                        <p class="text-base font-semibold text-gray-900">${data.dokter.nama}</p>
                        <p class="text-sm text-gray-600">${data.dokter.spesialisasi}</p>
                    </div>
                </div>
            </div>

            <!-- Keluhan & Anamnesa -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Keluhan & Anamnesa</h4>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Keluhan Utama</p>
                        <p class="text-base text-gray-900">${data.keluhan}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Anamnesa</p>
                        <p class="text-base text-gray-900">${data.anamnesa}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pemeriksaan Fisik</p>
                        <p class="text-base text-gray-900">${data.pemeriksaan_fisik}</p>
                    </div>
                </div>
            </div>

            <!-- Vital Signs -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Tanda Vital</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="border border-gray-400 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Tekanan Darah</p>
                        <p class="text-lg font-bold">${data.vital_signs.tekanan_darah}</p>
                    </div>
                    <div class="border border-gray-400 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Suhu Tubuh</p>
                        <p class="text-lg font-bold">${data.vital_signs.suhu_tubuh}</p>
                    </div>
                    <div class="border border-gray-400 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Berat Badan</p>
                        <p class="text-lg font-bold">${data.vital_signs.berat_badan}</p>
                    </div>
                    <div class="border border-gray-400 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Tinggi Badan</p>
                        <p class="text-lg font-bold">${data.vital_signs.tinggi_badan}</p>
                    </div>
                </div>
            </div>

            <!-- Diagnosa & Tindakan -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Diagnosa & Tindakan</h4>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Diagnosa</p>
                        <p class="text-base font-semibold text-gray-900">${data.diagnosa}</p>
                        ${data.icd10_code !== '-' ? `<p class="text-sm text-gray-600">Kode ICD-10: ${data.icd10_code}</p>` : ''}
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tindakan Medis</p>
                        <p class="text-base text-gray-900">${data.tindakan_medis}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Catatan Dokter</p>
                        <p class="text-base text-gray-900">${data.catatan_dokter}</p>
                    </div>
                </div>
            </div>
        `;

        if (data.resep) {
            html += `
                <div class="mb-6 border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Resep Obat</h4>
                    <div class="mb-2">
                        <span class="text-sm text-gray-500">Tanggal: </span>
                        <span class="text-sm font-medium">${data.resep.tanggal_resep}</span>
                        <span class="ml-3 px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                            data.resep.status === 'selesai' ? 'bg-green-100 text-green-800' :
                            data.resep.status === 'diproses' ? 'bg-yellow-100 text-yellow-800' :
                            'bg-gray-100 text-gray-800'
                        }">${data.resep.status.charAt(0).toUpperCase() + data.resep.status.slice(1)}</span>
                    </div>
                    <div class="overflow-x-auto mt-3">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Obat</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dosis</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aturan Pakai</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
            `;

            data.resep.obat.forEach(obat => {
                html += `
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">${obat.nama_obat}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">${obat.jumlah}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">${obat.dosis}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">${obat.aturan_pakai}</td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }

        if (data.lab.length > 0) {
            html += `
                <div class="mb-6 border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Hasil Laboratorium</h4>
            `;

            data.lab.forEach(lab => {
                html += `
                    <div class="mb-4 bg-gray-50 rounded-lg p-4">
                        <div class="mb-2">
                            <span class="text-sm font-medium text-gray-900">${lab.jenis_pemeriksaan}</span>
                            <span class="ml-3 px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                lab.status === 'selesai' ? 'bg-green-100 text-green-800' :
                                lab.status === 'diproses' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-gray-100 text-gray-800'
                            }">${lab.status.charAt(0).toUpperCase() + lab.status.slice(1)}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">Tanggal Permintaan: ${lab.tanggal_permintaan}</p>
                `;

                if (lab.hasil.length > 0) {
                    html += `
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Test</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Parameter</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Hasil</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Nilai Normal</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                    `;

                    lab.hasil.forEach(hasil => {
                        html += `
                            <tr>
                                <td class="px-3 py-2 text-gray-900">${hasil.jenis_test}</td>
                                <td class="px-3 py-2 text-gray-900">${hasil.parameter}</td>
                                <td class="px-3 py-2 font-semibold text-gray-900">${hasil.hasil} ${hasil.satuan}</td>
                                <td class="px-3 py-2 text-gray-600">${hasil.nilai_normal}</td>
                                <td class="px-3 py-2 text-gray-600">${hasil.tanggal_hasil}</td>
                            </tr>
                        `;
                    });

                    html += `
                            </tbody>
                        </table>
                    `;
                } else {
                    html += '<p class="text-sm text-gray-500">Belum ada hasil</p>';
                }

                // Tampilkan lampiran file hasil lab jika ada
                if (lab.file_hasil_url) {
                    const fileExtension = lab.file_hasil_url.split('.').pop().toLowerCase();
                    const isImage = ['jpg', 'jpeg', 'png'].includes(fileExtension);

                    html += `
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Lampiran Hasil Lab:</p>
                    `;

                    if (isImage) {
                        html += `
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <img src="${lab.file_hasil_url}"
                                     alt="Hasil Lab"
                                     class="w-full cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="window.open('${lab.file_hasil_url}', '_blank')">
                                <p class="text-xs text-gray-500 p-2 bg-gray-50 text-center">Klik gambar untuk melihat ukuran penuh</p>
                            </div>
                        `;
                    } else {
                        html += `
                            <a href="${lab.file_hasil_url}"
                               target="_blank"
                               class="flex items-center gap-2 p-3 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-700">Lihat Dokumen PDF</p>
                                    <p class="text-xs text-red-600">Klik untuk membuka</p>
                                </div>
                            </a>
                        `;
                    }

                    html += `</div>`;
                }

                html += '</div>';
            });

            html += '</div>';
        }

        if (data.rujukan) {
            html += `
                <div class="mb-6 border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Rujukan</h4>
                    <div class="border border-gray-400  rounded-lg p-4 space-y-2">
                        <div>
                            <p class="text-sm text-gray-600">Rumah Sakit Tujuan</p>
                            <p class="text-base font-semibold text-gray-900">${data.rujukan.rs_tujuan}</p>
                        </div>
                        ${data.rujukan.dokter_spesialis !== '-' ? `
                            <div>
                                <p class="text-sm text-gray-600">Dokter Spesialis Tujuan</p>
                                <p class="text-base text-gray-900">${data.rujukan.dokter_spesialis}</p>
                            </div>
                        ` : ''}
                        <div>
                            <p class="text-sm text-gray-600">Alasan Rujukan</p>
                            <p class="text-base text-gray-900">${data.rujukan.alasan_rujukan}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Diagnosa Sementara</p>
                            <p class="text-base text-gray-900">${data.rujukan.diagnosa_sementara}</p>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-600">Tujuan Rujukan: ${data.rujukan.tujuan_rujukan}</p>
                            <p class="text-xs text-gray-600">Tanggal Rujukan: ${data.rujukan.tanggal_rujukan}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        html += `
            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                <button onclick="closeDetailModal()"
                    class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] hover:cursor-pointer transition-all duration-200 shadow-md hover:shadow-lg">
                    Tutup
                </button>
            </div>
        `;

        modalContent.innerHTML = html;
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');

        document.body.style.overflow = '';

        const modalContainer = modal.querySelector('.max-h-\\[90vh\\]');
        if (modalContainer) {
            modalContainer.scrollTop = 0;
        }
    }

    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this || e.target.style.opacity === '0.2' ||
            (e.target.classList.contains('flex') && e.target.classList.contains('pl-64'))) {
            closeDetailModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('detailModal');
            if (!modal.classList.contains('hidden')) {
                closeDetailModal();
            }
        }
    });
</script>
@endsection
