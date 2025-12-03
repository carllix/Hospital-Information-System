@extends('layouts.dashboard')

@section('title', 'Stok Obat')
@section('dashboard-title', 'Manajemen Stok Obat')

@section('content')
<div class="space-y-6">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex flex-col lg:flex-row justify-between gap-4">
            
            <form method="GET" action="{{ route('farmasi.stok-obat') }}" class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode obat..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 focus:bg-white transition-all text-sm">
                </div>

                <div class="md:col-span-4 relative">
                    <select name="kategori" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                        <option value="">📂 Semua Kategori</option>
                        <option value="tablet" {{ $kategori == 'tablet' ? 'selected' : '' }}>💊 Tablet</option>
                        <option value="kapsul" {{ $kategori == 'kapsul' ? 'selected' : '' }}>💊 Kapsul</option>
                        <option value="sirup" {{ $kategori == 'sirup' ? 'selected' : '' }}>🧴 Sirup</option>
                        <option value="salep" {{ $kategori == 'salep' ? 'selected' : '' }}>🧴 Salep</option>
                        <option value="injeksi" {{ $kategori == 'injeksi' ? 'selected' : '' }}>💉 Injeksi</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <button type="submit" class="w-full py-2.5 bg-gray-800 text-white font-medium rounded-xl hover:bg-gray-900 transition-all shadow-md flex items-center justify-center gap-2">
                        Cari Obat
                    </button>
                </div>
            </form>

            <div class="border-t lg:border-t-0 lg:border-l border-gray-100 lg:pl-4 pt-4 lg:pt-0">
                <a href="{{ route('farmasi.tambah-obat') }}" class="inline-flex w-full lg:w-auto items-center justify-center px-6 py-2.5 bg-pink-600 text-white font-medium rounded-xl hover:bg-pink-700 transition-all shadow-md shadow-pink-200 group">
                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Obat Baru
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Inventori Obat</h3>
            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">Total: {{ $obatList->total() }}</span>
        </div>
        
        @if($obatList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-100 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Informasi Obat</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Status Stok</th>
                            <th class="px-6 py-4">Harga Satuan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($obatList as $obat)
                        <tr class="hover:bg-pink-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-pink-50 border border-pink-100 flex items-center justify-center text-pink-500 font-bold text-xs shrink-0">
                                        {{ substr($obat->nama_obat, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-800">{{ $obat->nama_obat }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $obat->kode_obat }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-lg bg-gray-100 text-gray-600 capitalize">
                                    {{ $obat->kategori }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($obat->stok == 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Habis
                                    </span>
                                @elseif($obat->stok < 10)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                                        {{ $obat->stok }} {{ $obat->satuan }} (Menipis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $obat->stok }} {{ $obat->satuan }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-gray-600">
                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openStokModal({{ $obat->obat_id }}, '{{ $obat->nama_obat }}', {{ $obat->stok }})" 
                                            class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all tooltip-trigger" title="Update Stok">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                    
                                    <a href="{{ route('farmasi.edit-obat', $obat->obat_id) }}" 
                                       class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>

                                    <form method="POST" action="{{ route('farmasi.delete-obat', $obat->obat_id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all" title="Hapus Obat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $obatList->links() }}
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Data Obat Tidak Ditemukan</h3>
                <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian atau kategori filter Anda.</p>
                <div class="mt-6">
                    <a href="{{ route('farmasi.stok-obat') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                        Reset Filter
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<div id="stokModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">📦 Update Stok Obat</h3>
                <button type="button" onclick="closeStokModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="stokForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                
                <div class="mb-6 bg-pink-50 rounded-xl p-4 border border-pink-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-pink-500 font-bold uppercase tracking-wide">Nama Obat</p>
                        <p id="namaObat" class="text-lg font-bold text-gray-800 mt-1"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-pink-500 font-bold uppercase tracking-wide">Stok Saat Ini</p>
                        <p id="stokSaatIni" class="text-lg font-mono font-bold text-gray-800 mt-1"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tindakan</label>
                        <select name="tipe" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white">
                            <option value="tambah">➕ Tambah Stok</option>
                            <option value="kurang">➖ Kurangi Stok</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="jumlah" min="1" required placeholder="0" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeStokModal()" class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-900 text-white font-medium rounded-xl hover:bg-black transition-colors shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStokModal(obatId, namaObat, stokSaatIni) {
    const modal = document.getElementById('stokModal');
    modal.classList.remove('hidden');
    // Animasi masuk sederhana
    setTimeout(() => {
        modal.querySelector('div[class*="transform"]').classList.remove('opacity-0', 'scale-95');
        modal.querySelector('div[class*="transform"]').classList.add('opacity-100', 'scale-100');
    }, 10);

    document.getElementById('namaObat').textContent = namaObat;
    document.getElementById('stokSaatIni').textContent = stokSaatIni;
    document.getElementById('stokForm').action = `/farmasi/obat/${obatId}/stok`;
}

function closeStokModal() {
    const modal = document.getElementById('stokModal');
    // Animasi keluar
    modal.querySelector('div[class*="transform"]').classList.remove('opacity-100', 'scale-100');
    modal.querySelector('div[class*="transform"]').classList.add('opacity-0', 'scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('stokForm').reset();
    }, 200); // Sesuaikan dengan durasi transisi CSS
}
</script>

@if(session('success'))
<x-toast type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
<x-toast type="error" message="{{ session('error') }}" />
@endif
@endsection