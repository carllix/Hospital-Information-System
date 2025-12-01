<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($pemeriksaan as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->tanggal_pemeriksaan->translatedFormat('j F Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->dokter->nama_lengkap }}</div>
                        @if($item->dokter->spesialisasi)
                            <div class="text-sm text-gray-500">{{ $item->dokter->spesialisasi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="max-w-xs truncate">{{ $item->pendaftaran->keluhan_utama ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="max-w-xs">
                            {{ $item->diagnosa ?? '-' }}
                            @if($item->icd10_code)
                                <span class="text-xs text-gray-500">({{ $item->icd10_code }})</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status_pasien == 'selesai_penanganan')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">
                                Selesai
                            </span>
                        @elseif($item->status_pasien == 'dirujuk')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-blue-100 text-blue-800">
                                Dirujuk
                            </span>
                        @elseif($item->status_pasien == 'perlu_resep')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-yellow-100 text-yellow-800">
                                Perlu Resep
                            </span>
                        @elseif($item->status_pasien == 'perlu_lab')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-orange-100 text-orange-800">
                                Perlu Lab
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openDetailModal({{ $item->pemeriksaan_id }})"
                            class="px-3 py-2 text-xs bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors duration-200 cursor-pointer">
                            Lihat Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada data rekam medis.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($pemeriksaan->hasPages())
    <div class="mt-6" id="paginationContainer">
        {{ $pemeriksaan->links() }}
    </div>
@endif
