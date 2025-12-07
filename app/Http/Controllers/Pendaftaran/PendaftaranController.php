<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Staf;
use App\Models\User;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PendaftaranController extends Controller
{
    public function dashboard(): View
    {
        return view('pendaftaran.dashboard');
    }

    public function pasienBaru(): View
    {
        return view('pendaftaran.pasien-baru');
    }

    public function storePasienBaru(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:pasien,nik',
            'nama_lengkap' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'tempat_lahir' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'golongan_darah' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email|unique:users,email',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->nik),
                'role' => 'pasien',
            ]);

            $noRM = $user->generateNoRekamMedis();

            $pasien = Pasien::create([
                'user_id' => $user->user_id,
                'no_rekam_medis' => $noRM,
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tempat_lahir' => $request->tempat_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'kota_kabupaten' => $request->kota_kabupaten,
                'kecamatan' => $request->kecamatan,
                'provinsi' => $request->provinsi,
                'no_telepon' => $request->no_telepon,
                'golongan_darah' => $request->golongan_darah,
            ]);

            DB::commit();
            return redirect()->route('pendaftaran.pasien-baru')
                ->with('success', "Pasien berhasil didaftarkan! No. Rekam Medis: {$noRM}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pendaftaran.pasien-baru')
                ->with('error', 'Pendaftaran pasien gagal: ' . $e->getMessage());
        }
    }

    public function kunjungan(): View
    {
        $dokters = Dokter::select('dokter_id', 'nama_lengkap', 'spesialisasi', 'jadwal_praktik')
            ->orderBy('nama_lengkap')
            ->get();

        return view('pendaftaran.kunjungan', compact('dokters'));
    }

    public function searchPasien(Request $request)
    {
        $request->validate([
            'search' => 'required|string|size:16',
        ]);

        $search = $request->input('search');

        $pasien = Pasien::where('nik', $search)->get();

        return response()->json($pasien);
    }

    public function storePendaftaran(Request $request): RedirectResponse
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasien,pasien_id',
            'dokter_id' => 'required|exists:dokter,dokter_id',
            'keluhan_utama' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $today = now()->format('Y-m-d');
            $lastAntrian = Pendaftaran::where('dokter_id', $request->dokter_id)
                ->whereDate('tanggal_daftar', $today)
                ->count();

            $dokter = Dokter::find($request->dokter_id);
            $initial = strtoupper(substr($dokter->nama_lengkap, 0, 1));
            $nomorAntrian = $initial . str_pad($lastAntrian + 1, 3, '0', STR_PAD_LEFT);

            $staf = Staf::where('user_id', auth()->id())->first();

            Pendaftaran::create([
                'pasien_id' => $request->pasien_id,
                'dokter_id' => $request->dokter_id,
                'tanggal_daftar' => now(),
                'nomor_antrian' => $nomorAntrian,
                'keluhan_utama' => $request->keluhan_utama,
                'staf_pendaftaran_id' => $staf?->staf_id,
                'status' => 'menunggu',
            ]);

            DB::commit();
            return redirect()->route('pendaftaran.kunjungan')
                ->with('success', "Pendaftaran berhasil! Nomor Antrian: {$nomorAntrian} - {$dokter->nama_lengkap}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pendaftaran.kunjungan')
                ->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
        }
    }

    public function dataPasien(Request $request): View
    {
        $query = Pasien::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(nik) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(no_rekam_medis) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $pasiens = $query->orderBy('no_rekam_medis', 'desc')->paginate(10);

        return view('pendaftaran.data-pasien', compact('pasiens'));
    }

    public function antrian(Request $request): View
    {
        $query = Pendaftaran::with(['pasien', 'dokter'])
            ->whereDate('tanggal_daftar', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->orderBy('nomor_antrian');

        if ($request->filled('dokter_id')) {
            $query->where('dokter_id', $request->dokter_id);
        }

        $pendaftarans = $query->get();
        $dokters = Dokter::select('dokter_id', 'nama_lengkap')->orderBy('nama_lengkap')->get();

        return view('pendaftaran.antrian', compact('pendaftarans', 'dokters'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:dipanggil',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($pendaftaran->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak dapat diubah'
            ], 400);
        }

        $pendaftaran->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah menjadi ' . $request->status
        ]);
    }

    public function jadwalDokter(Request $request): View
    {
        $query = Dokter::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"]);
        }

        if ($request->filled('spesialisasi')) {
            $query->where('spesialisasi', $request->spesialisasi);
        }

        $dokters = $query->orderBy('nama_lengkap')->get();

        $spesialisasiList = [
            'Umum',
            'Penyakit Dalam',
            'Anak',
            'Kandungan',
            'Jantung',
            'Bedah',
            'Mata',
            'THT',
            'Kulit dan Kelamin',
            'Saraf',
            'Jiwa',
            'Paru',
            'Orthopedi',
            'Urologi',
            'Radiologi',
            'Anestesi',
            'Patologi Klinik'
        ];

        return view('pendaftaran.jadwal-dokter', compact('dokters', 'spesialisasiList'));
    }

    public function riwayat(Request $request): View
    {
        $query = Pendaftaran::with(['pasien', 'dokter', 'stafPendaftaran'])
            ->orderBy('tanggal_daftar', 'desc');

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_daftar', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_daftar', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dokter_id')) {
            $query->where('dokter_id', $request->dokter_id);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(nik) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(no_rekam_medis) LIKE ?', ["%{$search}%"]);
            });
        }

        $pendaftarans = $query->paginate(10);
        $dokters = Dokter::select('dokter_id', 'nama_lengkap')->orderBy('nama_lengkap')->get();

        return view('pendaftaran.riwayat', compact('pendaftarans', 'dokters'));
    }

    public function profile(): View
    {
        $staf = Staf::where('user_id', auth()->id())->firstOrFail();
        return view('pendaftaran.profile', compact('staf'));
    }

    public function editProfile(): View
    {
        $staf = Staf::where('user_id', auth()->id())->firstOrFail();
        return view('pendaftaran.edit-profile', compact('staf'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $staf = Staf::where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Validate and update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }

            auth()->user()->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        try {
            $staf->update($validated);

            $message = 'Profil berhasil diperbarui.';
            if ($request->filled('new_password')) {
                $message = 'Profil dan password berhasil diperbarui.';
            }

            return redirect()->route('pendaftaran.profile')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profil.'])->withInput();
        }
    }
}
