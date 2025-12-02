<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Staf;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $this->validateRegistration($request);

        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
            ]);

            $this->createRoleSpecificData($user, $validated);

            DB::commit();

            Auth::login($user);

            return redirect($user->getDashboardRoute());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateRegistration(Request $request): array
    {
        $rules = [
            'role' => ['required', 'in:pasien,pendaftaran,dokter,apoteker,lab,kasir_klinik,kasir_apotek,kasir_lab'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];

        $role = $request->input('role');

        if ($role === 'pasien') {
            $rules = array_merge($rules, [
                'nama_lengkap' => ['required', 'string', 'max:100'],
                'nik' => ['required', 'string', 'size:16', 'unique:pasien,nik'],
                'tanggal_lahir' => ['required', 'date', 'before:today'],
                'jenis_kelamin' => ['required', 'in:Laki-Laki,Perempuan'],
                'alamat' => ['required', 'string'],
                'provinsi' => ['nullable', 'string', 'max:100'],
                'kota_kabupaten' => ['nullable', 'string', 'max:100'],
                'kecamatan' => ['nullable', 'string', 'max:100'],
                'kewarganegaraan' => ['nullable', 'string', 'max:50'],
                'no_telepon' => ['required', 'string', 'max:15'],
                'golongan_darah' => ['nullable', 'in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-'],
            ]);
        } elseif ($role === 'dokter') {
            $rules = array_merge($rules, [
                'nama_lengkap' => ['required', 'string', 'max:100'],
                'nik' => ['required', 'string', 'size:16', 'unique:dokter,nik'],
                'tanggal_lahir' => ['required', 'date', 'before:today'],
                'jenis_kelamin' => ['required', 'in:Laki-Laki,Perempuan'],
                'alamat' => ['required', 'string'],
                'provinsi' => ['nullable', 'string', 'max:100'],
                'kota_kabupaten' => ['nullable', 'string', 'max:100'],
                'kecamatan' => ['nullable', 'string', 'max:100'],
                'kewarganegaraan' => ['nullable', 'string', 'max:50'],
                'no_telepon' => ['required', 'string', 'max:15'],
                'spesialisasi' => ['nullable', 'string', 'max:100'],
                'no_str' => ['nullable', 'string', 'max:50'],
            ]);
        } elseif (in_array($role, ['pendaftaran', 'apoteker', 'lab', 'kasir_klinik', 'kasir_apotek', 'kasir_lab'])) {
            $rules = array_merge($rules, [
                'nama_lengkap' => ['required', 'string', 'max:100'],
                'nik' => ['required', 'string', 'size:16', 'unique:staf,nik'],
                'tanggal_lahir' => ['required', 'date', 'before:today'],
                'jenis_kelamin' => ['required', 'in:Laki-Laki,Perempuan'],
                'alamat' => ['required', 'string'],
                'provinsi' => ['nullable', 'string', 'max:100'],
                'kota_kabupaten' => ['nullable', 'string', 'max:100'],
                'kecamatan' => ['nullable', 'string', 'max:100'],
                'kewarganegaraan' => ['nullable', 'string', 'max:50'],
                'no_telepon' => ['required', 'string', 'max:15'],
            ]);
        }

        return $request->validate($rules);
    }

    private function createRoleSpecificData(User $user, array $validated): void
    {
        $role = $validated['role'];

        if ($role === 'pasien') {
            Pasien::create([
                'user_id' => $user->user_id,
                'no_rekam_medis' => $this->generateNoRekamMedis(),
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'provinsi' => $validated['provinsi'] ?? null,
                'kota_kabupaten' => $validated['kota_kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kewarganegaraan' => $validated['kewarganegaraan'] ?? null,
                'no_telepon' => $validated['no_telepon'],
                'golongan_darah' => $validated['golongan_darah'] ?? null,
            ]);
        } elseif ($role === 'dokter') {
            Dokter::create([
                'user_id' => $user->user_id,
                'nip' => $this->generateNipRS(),
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'provinsi' => $validated['provinsi'] ?? null,
                'kota_kabupaten' => $validated['kota_kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kewarganegaraan' => $validated['kewarganegaraan'] ?? null,
                'no_telepon' => $validated['no_telepon'],
                'spesialisasi' => $validated['spesialisasi'] ?? null,
                'no_str' => $validated['no_str'] ?? null,
            ]);
        } elseif (in_array($role, ['pendaftaran', 'apoteker', 'lab', 'kasir_klinik', 'kasir_apotek', 'kasir_lab'])) {
            $bagianMap = [
                'pendaftaran' => 'pendaftaran',
                'apoteker' => 'farmasi',
                'lab' => 'laboratorium',
                'kasir_klinik' => 'kasir',
                'kasir_apotek' => 'kasir',
                'kasir_lab' => 'kasir',
            ];

            Staf::create([
                'user_id' => $user->user_id,
                'nip' => $this->generateNipRS(),
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'provinsi' => $validated['provinsi'] ?? null,
                'kota_kabupaten' => $validated['kota_kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kewarganegaraan' => $validated['kewarganegaraan'] ?? null,
                'no_telepon' => $validated['no_telepon'],
                'bagian' => $bagianMap[$role],
            ]);
        }
    }

    private function generateNoRekamMedis(): string
    {
        $lastPasien = Pasien::orderBy('pasien_id', 'desc')->first();

        if (!$lastPasien) {
            return 'RM-00001';
        }

        $lastNumber = (int) substr($lastPasien->no_rekam_medis, 3);
        $newNumber = $lastNumber + 1;

        return 'RM-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    private function generateNipRS(): string
    {
        // Get the last NIP from dokter table
        $lastDokter = Dokter::orderBy('dokter_id', 'desc')->first();
        $lastNumberDokter = 0;
        if ($lastDokter && $lastDokter->nip) {
            $lastNumberDokter = (int) substr($lastDokter->nip, 5);
        }

        // Get the last NIP from staf table
        $lastStaf = Staf::orderBy('staf_id', 'desc')->first();
        $lastNumberStaf = 0;
        if ($lastStaf && $lastStaf->nip) {
            $lastNumberStaf = (int) substr($lastStaf->nip, 5);
        }

        $lastNumber = max($lastNumberDokter, $lastNumberStaf);
        $newNumber = $lastNumber + 1;

        return 'NIPRS' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
