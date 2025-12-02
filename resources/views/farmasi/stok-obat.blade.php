@extends('layouts.dashboard')

@section('title', 'Stok Obat')
@section('dashboard-title', 'Manajemen Stok Obat')

@section('content')
<div class="space-y-6">
    <!-- Filter & Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-end space-y-4 md:space-y-0 md:space-x-4">
            <form method="GET" action="{{ route('farmasi.stok-obat') }}" class="flex-1 flex space-x-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Obat</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau kode obat..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                </div>
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                        <option value="">Semua Kategori</option>
                        <option value="tablet" {{ $kategori == 'tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="kapsul" {{ $kategori == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                        <option value="sirup" {{ $kategori == 'sirup' ? 'selected' : '' }}>Sirup</option>
                        <option value="salep" {{ $kategori == 'salep' ? 'selected' : '' }}>Salep</option>
                        <option value="injeksi" {{ $kategori == 'injeksi' ? 'selected' : '' }}>Injeksi</option>
                    </select>
                </div>
                <div class="pt-7">
                    <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                        Cari
                    </button>
                </div>
            </form>
            
            <div class="pt-7">
                <a href="{{ route('farmasi.tambah-obat') }}" class="inline-flex items-center px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Obat
                </a>
            </div>
        </div>
    </div>

    <!-- Obat List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Obat</h3>
        </div>
        
        @if($obatList->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Obat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($obatList as $obat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $obat->kode_obat }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $obat->nama_obat }}</div>
                                @if($obat->deskripsi)
                                    <div class="text-xs text-gray-500">{{ Str::limit($obat->deskripsi, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($obat->kategori) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($obat->stok == 0)
                                    <span class="text-sm font-bold text-red-600">Habis</span>
                                @elseif($obat->stok < 10)
                                    <span class="text-sm font-bold text-orange-600">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                @else
                                    <span class="text-sm font-medium text-green-600">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <!-- Update Stok Modal Trigger -->
                                <button onclick="openStokModal({{ $obat->obat_id }}, '{{ $obat->nama_obat }}', {{ $obat->stok }})" class="text-blue-600 hover:text-blue-900 font-medium">
                                    Stok
                                </button>
                                <a href="{{ route('farmasi.edit-obat', $obat->obat_id) }}" class="text-green-600 hover:text-green-900 font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('farmasi.delete-obat', $obat->obat_id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $obatList->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada obat</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada data obat atau hasil pencarian tidak ditemukan.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Update Stok -->
<div id="stokModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Update Stok Obat</h3>
            <button onclick="closeStokModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="stokForm" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Obat</label>
                <p id="namaObat" class="text-sm text-gray-900 font-medium"></p>
                <p class="text-xs text-gray-500">Stok saat ini: <span id="stokSaatIni"></span></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                <select name="tipe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    <option value="tambah">Tambah Stok</option>
                    <option value="kurang">Kurangi Stok</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                <input type="number" name="jumlah" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    Update Stok
                </button>
                <button type="button" onclick="closeStokModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openStokModal(obatId, namaObat, stokSaatIni) {
    document.getElementById('stokModal').classList.remove('hidden');
    document.getElementById('namaObat').textContent = namaObat;
    document.getElementById('stokSaatIni').textContent = stokSaatIni;
    document.getElementById('stokForm').action = `/farmasi/obat/${obatId}/stok`;
}

function closeStokModal() {
    document.getElementById('stokModal').classList.add('hidden');
    document.getElementById('stokForm').reset();
}
</script>

@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif
@endsection
