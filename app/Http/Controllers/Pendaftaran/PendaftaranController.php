<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use App\Mail\PasswordNotification;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Staf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    public function dashboard(): View
    {
        $staf = auth()->user()->staf;

        // Pendaftaran Hari Ini
        $pendaftaranHariIni = Pendaftaran::whereDate('tanggal_daftar', today())->count();

        // Total Pasien Terdaftar
        $totalPasien = Pasien::where('is_deleted', false)->count();

        // Antrian Menunggu (hari ini)
        $antrianMenunggu = Pendaftaran::whereDate('tanggal_kunjungan', today())
            ->where('status', 'menunggu')
            ->count();

        // Pendaftaran oleh staf ini (bulan ini)
        $pendaftaranBulanIni = Pendaftaran::where('staf_pendaftaran_id', $staf->staf_id)
            ->whereMonth('tanggal_daftar', now()->month)
            ->whereYear('tanggal_daftar', now()->year)
            ->count();

        return view('pendaftaran.dashboard', compact(
            'pendaftaranHariIni',
            'totalPasien',
            'antrianMenunggu',
            'pendaftaranBulanIni'
        ));
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
            // Generate random password
            $randomPassword = Str::random(12);

            $user = User::create([
                'email' => $request->email,
                'password' => $randomPassword,
                'role' => 'pasien',
                'is_deleted' => false,
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

            // Send password notification email
            try {
                Mail::to($user->email)->send(
                    new PasswordNotification(
                        $request->nama_lengkap,
                        $user->email,
                        $randomPassword,
                        $noRM
                    )
                );
            } catch (\Exception $e) {
                // Log email error but don't fail registration
                Log::error('Failed to send password notification email: ' . $e->getMessage());
            }

            DB::commit();
            return redirect()->route('pendaftaran.pasien-baru')
                ->with('success', "Pasien berhasil didaftarkan! No. Rekam Medis: {$noRM}. Password telah dikirim ke email pasien.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pendaftaran.pasien-baru')
                ->with('error', 'Pendaftaran pasien gagal: ' . $e->getMessage());
        }
    }

    public function kunjungan(): View
    {
        // Get list of unique specializations from active doctors
        $spesialisasiList = Dokter::where('is_deleted', false)
            ->distinct()
            ->orderBy('spesialisasi')
            ->pluck('spesialisasi');

        return view('pendaftaran.kunjungan', compact('spesialisasiList'));
    }

    public function getDokterBySpesialisasi(Request $request)
    {
        $request->validate([
            'spesialisasi' => 'required|string',
        ]);

        $dokters = Dokter::where('spesialisasi', $request->spesialisasi)
            ->where('is_deleted', false)
            ->select('dokter_id', 'nama_lengkap', 'spesialisasi')
            ->orderBy('nama_lengkap')
            ->get();

        return response()->json($dokters);
    }

    public function getJadwalByDokter(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokter,dokter_id',
        ]);

        $jadwals = JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('is_deleted', false)
            ->orderBy('hari_praktik')
            ->orderBy('waktu_mulai')
            ->get()
            ->map(function ($jadwal) {
                return [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'hari_praktik' => $jadwal->hari_praktik,
                    'waktu_mulai' => \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i'),
                    'waktu_selesai' => \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i'),
                    'max_pasien' => $jadwal->max_pasien,
                ];
            });

        return response()->json($jadwals);
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
            'jadwal_id' => 'required|exists:jadwal_dokter,jadwal_id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'keluhan_utama' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Get jadwal with dokter information
            $jadwal = JadwalDokter::with('dokter')->findOrFail($request->jadwal_id);

            // Count existing registrations for this doctor on the visit date
            $lastAntrian = Pendaftaran::whereHas('jadwalDokter', function ($q) use ($jadwal) {
                $q->where('dokter_id', $jadwal->dokter_id);
            })
                ->whereDate('tanggal_kunjungan', $request->tanggal_kunjungan)
                ->count();

            // Check if max_pasien limit is reached
            if ($lastAntrian >= $jadwal->max_pasien) {
                DB::rollBack();
                return redirect()->route('pendaftaran.kunjungan')
                    ->with('error', "Kuota pendaftaran untuk jadwal ini sudah penuh (max: {$jadwal->max_pasien} pasien)");
            }

            $nameParts = explode(' ', trim($jadwal->dokter->nama_lengkap));
            if (count($nameParts) >= 2) {
                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
            } else {
                $initials = 'D' . strtoupper(substr($nameParts[0], 0, 1));
            }

            $dokterId = str_pad($jadwal->dokter_id, 3, '0', STR_PAD_LEFT);

            $tanggal = \Carbon\Carbon::parse($request->tanggal_kunjungan)->format('dmY');

            $queueNumber = str_pad($lastAntrian + 1, 2, '0', STR_PAD_LEFT);

            $nomorAntrian = $initials . $dokterId . $tanggal . $queueNumber;

            $staf = Staf::where('user_id', auth()->id())->first();

            Pendaftaran::create([
                'pasien_id' => $request->pasien_id,
                'jadwal_id' => $request->jadwal_id,
                'tanggal_daftar' => now(),
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'nomor_antrian' => $nomorAntrian,
                'keluhan_utama' => $request->keluhan_utama,
                'staf_pendaftaran_id' => $staf?->staf_id,
                'status' => 'menunggu',
            ]);

            DB::commit();
            return redirect()->route('pendaftaran.kunjungan')
                ->with('success', "Pendaftaran berhasil! Nomor Antrian: {$nomorAntrian} - Dr. {$jadwal->dokter->nama_lengkap} ({$jadwal->hari_praktik}, {$request->tanggal_kunjungan})");
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
        $tanggal = $request->filled('tanggal') ? $request->tanggal : today()->format('Y-m-d');

        $query = Pendaftaran::with(['pasien', 'jadwalDokter.dokter'])
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->orderBy('nomor_antrian');

        if ($request->filled('dokter_id')) {
            $query->whereHas('jadwalDokter', function ($q) use ($request) {
                $q->where('dokter_id', $request->dokter_id);
            });
        }

        $pendaftarans = $query->get();
        $dokters = Dokter::where('is_deleted', false)
            ->select('dokter_id', 'nama_lengkap')
            ->orderBy('nama_lengkap')
            ->get();

        return view('pendaftaran.antrian', compact('pendaftarans', 'dokters', 'tanggal'));
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
        $query = Dokter::with('jadwalDokter')->where('is_deleted', false);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"]);
        }

        if ($request->filled('spesialisasi')) {
            $query->where('spesialisasi', $request->spesialisasi);
        }

        $dokters = $query->orderBy('nama_lengkap')->get();

        // Transform jadwal data for the view
        $dokters->each(function ($dokter) {
            $dokter->jadwal_praktik = $dokter->jadwalDokter->where('is_deleted', false)->map(function ($jadwal) {
                return [
                    'hari' => $jadwal->hari_praktik,
                    'jam_mulai' => \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i'),
                    'jam_selesai' => \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i'),
                    'max_pasien' => $jadwal->max_pasien,
                ];
            })->values();
        });

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

        $routeName = 'pendaftaran.jadwal-dokter';

        return view('pendaftaran.jadwal-dokter', compact('dokters', 'spesialisasiList', 'routeName'));
    }

    public function riwayat(Request $request): View
    {
        $query = Pendaftaran::with(['pasien', 'jadwalDokter.dokter', 'stafPendaftaran'])
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
            $query->whereHas('jadwalDokter', function ($q) use ($request) {
                $q->where('dokter_id', $request->dokter_id);
            });
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
        $dokters = Dokter::where('is_deleted', false)
            ->select('dokter_id', 'nama_lengkap')
            ->orderBy('nama_lengkap')
            ->get();

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
                'password' => $request->new_password
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
