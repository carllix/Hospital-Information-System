@extends('layouts.dashboard')

@section('title', 'Edit Profil - Staff Pendaftaran')
@section('dashboard-title', 'Edit Profil')

@section('content')
@if($errors->has('error'))
    <x-toast type="error" :message="$errors->first('error')" />
@endif

<div class="w-full min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profil</h2>

        <form method="POST" action="{{ route('pendaftaran.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP RS</label>
                    <input type="text" id="nip" value="{{ $staf->nip }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">NIP RS tidak dapat diubah</p>
                </div>

                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" id="nik" value="{{ $staf->nik }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">NIK tidak dapat diubah</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" value="{{ $staf->user->email }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah</p>
                </div>

                <div>
                    <label for="bagian" class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                    <input type="text" id="bagian" value="{{ ucfirst($staf->bagian) }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Bagian tidak dapat diubah</p>
                </div>

                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $staf->nama_lengkap) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $staf->tanggal_lahir->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('tanggal_lahir') border-red-500 @enderror">
                    @error('tanggal_lahir')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki" {{ old('jenis_kelamin', $staf->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $staf->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No Telepon <span class="text-red-500">*</span></label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $staf->no_telepon) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('no_telepon') border-red-500 @enderror">
                    @error('no_telepon')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kewarganegaraan" class="block text-sm font-medium text-gray-700 mb-2">Kewarganegaraan</label>
                    <input type="text" id="kewarganegaraan" name="kewarganegaraan" value="{{ old('kewarganegaraan', $staf->kewarganegaraan) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('kewarganegaraan') border-red-500 @enderror">
                    @error('kewarganegaraan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                    <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi', $staf->provinsi) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('provinsi') border-red-500 @enderror">
                    @error('provinsi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kota_kabupaten" class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                    <input type="text" id="kota_kabupaten" name="kota_kabupaten" value="{{ old('kota_kabupaten', $staf->kota_kabupaten) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('kota_kabupaten') border-red-500 @enderror">
                    @error('kota_kabupaten')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                    <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $staf->kecamatan) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('kecamatan') border-red-500 @enderror">
                    @error('kecamatan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                <textarea id="alamat" name="alamat" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#f56e9d] @error('alamat') border-red-500 @enderror">{{ old('alamat', $staf->alamat) }}</textarea>
                @error('alamat')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('pendaftaran.profile') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-[#f56e9d] text-white rounded-lg hover:bg-[#d14a7a] transition-colors hover:cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
