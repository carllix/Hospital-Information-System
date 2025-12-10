@extends('layouts.dashboard')

@section('title', 'Detail Dokter')
@section('dashboard-title', 'Detail Dokter')

@section('content')
<x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />

<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Dokter</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap dokter</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.dokter.edit', $dokter->dokter_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                    Edit Data
                </a>
                <a href="{{ route('admin.dokter.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Akun</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">NIP RS</p>
                        <p class="text-base font-semibold text-[#f56e9d]">{{ $dokter->nip_rs }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-base font-medium">{{ $dokter->user->email }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Data Pribadi</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="text-base font-medium">{{ $dokter->nama_lengkap }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">NIK</p>
                            <p class="text-base font-medium">{{ $dokter->nik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jenis Kelamin</p>
                            <p class="text-base font-medium">{{ $dokter->jenis_kelamin }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Tempat & Tanggal Lahir</h3>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tempat Lahir</p>
                            <p class="text-base font-medium">{{ $dokter->tempat_lahir }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Lahir</p>
                            <p class="text-base font-medium">{{ $dokter->tanggal_lahir->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Usia</p>
                        <p class="text-base font-medium">{{ \Carbon\Carbon::parse($dokter->tanggal_lahir)->age }} tahun</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Kontak</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="text-base font-medium">{{ $dokter->no_telepon }}</p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Alamat</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Alamat Lengkap</p>
                        <p class="text-base font-medium">{{ $dokter->alamat }}</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Kecamatan</p>
                            <p class="text-base font-medium">{{ $dokter->kecamatan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kota/Kabupaten</p>
                            <p class="text-base font-medium">{{ $dokter->kota_kabupaten }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Provinsi</p>
                            <p class="text-base font-medium">{{ $dokter->provinsi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Profesi</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Spesialisasi</p>
                        <p class="text-base font-medium">
                            <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">{{ $dokter->spesialisasi }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. STR</p>
                        <p class="text-base font-medium">{{ $dokter->no_str }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Dokter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Jadwal Praktik</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola jadwal praktik dokter</p>
            </div>
            <button onclick="showAddJadwalModal()" class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Jadwal
            </button>
        </div>

        @if($dokter->jadwalDokter->where('is_deleted', false)->isEmpty())
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="mt-4 text-gray-600">Belum ada jadwal praktik</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Pasien</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($dokter->jadwalDokter->where('is_deleted', false)->sortBy(function($jadwal) {
                        $days = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
                        return $days[$jadwal->hari_praktik] ?? 8;
                    }) as $jadwal)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">{{ $jadwal->hari_praktik }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->max_pasien }} pasien</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showEditJadwalModal({{ json_encode($jadwal) }})"
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                        title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.dokter.jadwal.destroy', [$dokter->dokter_id, $jadwal->jadwal_id]) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div id="addJadwalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Jadwal Praktik</h3>
        <form action="{{ route('admin.dokter.jadwal.store', $dokter->dokter_id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="hari_praktik" class="block text-sm font-medium text-gray-700 mb-2">Hari <span class="text-red-500">*</span></label>
                <select name="hari_praktik" id="hari_praktik" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                    <option value="">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                </div>
                <div>
                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                </div>
            </div>
            <div>
                <label for="max_pasien" class="block text-sm font-medium text-gray-700 mb-2">Max Pasien <span class="text-red-500">*</span></label>
                <input type="number" name="max_pasien" id="max_pasien" min="1" max="100" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
            </div>
            <div class="flex items-center justify-end gap-2 pt-4">
                <button type="button" onclick="hideAddJadwalModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div id="editJadwalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Jadwal Praktik</h3>
        <form id="editJadwalForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_hari_praktik" class="block text-sm font-medium text-gray-700 mb-2">Hari <span class="text-red-500">*</span></label>
                <select name="hari_praktik" id="edit_hari_praktik" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                    <option value="">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit_waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu_mulai" id="edit_waktu_mulai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                </div>
                <div>
                    <label for="edit_waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu_selesai" id="edit_waktu_selesai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                </div>
            </div>
            <div>
                <label for="edit_max_pasien" class="block text-sm font-medium text-gray-700 mb-2">Max Pasien <span class="text-red-500">*</span></label>
                <input type="number" name="max_pasien" id="edit_max_pasien" min="1" max="100" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
            </div>
            <div class="flex items-center justify-end gap-2 pt-4">
                <button type="button" onclick="hideEditJadwalModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#e05d8c] transition-colors">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddJadwalModal() {
    document.getElementById('addJadwalModal').classList.remove('hidden');
    document.getElementById('addJadwalModal').classList.add('flex');
}

function hideAddJadwalModal() {
    document.getElementById('addJadwalModal').classList.add('hidden');
    document.getElementById('addJadwalModal').classList.remove('flex');
}

function showEditJadwalModal(jadwal) {
    const form = document.getElementById('editJadwalForm');
    form.action = `/admin/dokter/{{ $dokter->dokter_id }}/jadwal/${jadwal.jadwal_id}`;

    document.getElementById('edit_hari_praktik').value = jadwal.hari_praktik;
    document.getElementById('edit_waktu_mulai').value = jadwal.waktu_mulai.substring(0, 5);
    document.getElementById('edit_waktu_selesai').value = jadwal.waktu_selesai.substring(0, 5);
    document.getElementById('edit_max_pasien').value = jadwal.max_pasien;

    document.getElementById('editJadwalModal').classList.remove('hidden');
    document.getElementById('editJadwalModal').classList.add('flex');
}

function hideEditJadwalModal() {
    document.getElementById('editJadwalModal').classList.add('hidden');
    document.getElementById('editJadwalModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('addJadwalModal').addEventListener('click', function(e) {
    if (e.target === this) hideAddJadwalModal();
});

document.getElementById('editJadwalModal').addEventListener('click', function(e) {
    if (e.target === this) hideEditJadwalModal();
});
</script>
@endsection
