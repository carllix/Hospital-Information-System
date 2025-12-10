<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\User;
use App\Models\Staf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordNotification;
use Illuminate\Support\Facades\Log;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokter::where('is_deleted', false)->with('user');

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(nip_rs) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(nik) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(spesialisasi) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('spesialisasi')) {
            $query->where('spesialisasi', $request->spesialisasi);
        }

        $dokters = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get unique specializations for filter
        $spesialisasiList = Dokter::where('is_deleted', false)
            ->select('spesialisasi')
            ->distinct()
            ->orderBy('spesialisasi')
            ->pluck('spesialisasi');

        return view('admin.dokter.index', compact('dokters', 'spesialisasiList'));
    }

    public function create()
    {
        return view('admin.dokter.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:dokter,nik',
            'nama_lengkap' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'spesialisasi' => 'required|string|max:100',
            'no_str' => 'required|string|max:17',
            'golongan_darah' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email|unique:users,email',
        ]);

        DB::beginTransaction();

        try {
            // Generate random password
            $randomPassword = Str::random(12);

            // Create user account
            $user = User::create([
                'email' => $validated['email'],
                'password' => $randomPassword,
                'role' => 'dokter',
                'is_deleted' => false,
            ]);

            $nip_rs = $this->generateNipRS();
            // Create dokter record
            $dokter = Dokter::create([
                'user_id' => $user->user_id,
                'nip_rs' => $nip_rs,
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'provinsi' => $validated['provinsi'],
                'kota_kabupaten' => $validated['kota_kabupaten'],
                'kecamatan' => $validated['kecamatan'],
                'no_telepon' => $validated['no_telepon'],
                'spesialisasi' => $validated['spesialisasi'],
                'no_str' => $validated['no_str'],
                'is_deleted' => false,
            ]);

            // Send password notification email
            try {
                Mail::to($user->email)->send(
                    new PasswordNotification(
                        namaLengkap: $validated['nama_lengkap'],
                        email: $user->email,
                        password: $randomPassword,
                        role: 'dokter',
                        identifier: $nip_rs
                    )
                );
            } catch (\Exception $e) {
                // Log email error but don't fail registration
                Log::error('Failed to send password notification email: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('admin.dokter.show', $dokter->dokter_id)
                ->with('success', "Dokter berhasil ditambahkan! NIP RS: {$nip_rs}. Password telah dikirim ke email dokter." );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Pendaftaran Dokter gagal: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $dokter = Dokter::with('user', 'jadwalDokter')->findOrFail($id);

        if ($dokter->is_deleted) {
            abort(404);
        }

        return view('admin.dokter.show', compact('dokter'));
    }

    public function edit(string $id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);

        if ($dokter->is_deleted) {
            abort(404);
        }

        return view('admin.dokter.edit', compact('dokter'));
    }

    public function update(Request $request, string $id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);

        if ($dokter->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($dokter->user_id, 'user_id')],
            'nama_lengkap' => 'required|string|max:100',
            'nik' => ['required', 'string', 'size:16', Rule::unique('dokter', 'nik')->ignore($id, 'dokter_id')],
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'spesialisasi' => 'required|string|max:100',
            'no_str' => 'required|string|max:17',
        ]);

        DB::beginTransaction();

        try {
            // Update user email
            $dokter->user->update([
                'email' => $validated['email'],
            ]);

            // Update dokter
            $dokter->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'provinsi' => $validated['provinsi'],
                'kota_kabupaten' => $validated['kota_kabupaten'],
                'kecamatan' => $validated['kecamatan'],
                'no_telepon' => $validated['no_telepon'],
                'spesialisasi' => $validated['spesialisasi'],
                'no_str' => $validated['no_str'],
            ]);

            DB::commit();

            return redirect()->route('admin.dokter.show', $dokter->dokter_id)
                ->with('success', 'Data dokter berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            $dokter->update(['is_deleted' => true]);
            $dokter->user->update(['is_deleted' => true]);

            DB::commit();

            return redirect()->route('admin.dokter.index')
                ->with('success', 'Dokter berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateNipRS(): string
    {
        // Get the last NIP from dokter table
        $lastDokter = Dokter::orderBy('dokter_id', 'desc')->first();
        $lastNumberDokter = 0;
        if ($lastDokter && $lastDokter->nip_rs) {
            $lastNumberDokter = (int) substr($lastDokter->nip_rs, 5);
        }

        // Get the last NIP from staf table
        $lastStaf = Staf::orderBy('staf_id', 'desc')->first();
        $lastNumberStaf = 0;
        if ($lastStaf && $lastStaf->nip_rs) {
            $lastNumberStaf = (int) substr($lastStaf->nip_rs, 5);
        }

        $lastNumber = max($lastNumberDokter, $lastNumberStaf);
        $newNumber = $lastNumber + 1;

        return 'NIPRS' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    // ==================== JADWAL DOKTER MANAGEMENT ====================

    public function storeJadwal(Request $request, string $id)
    {
        $dokter = Dokter::findOrFail($id);

        if ($dokter->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'hari_praktik' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'max_pasien' => 'required|integer|min:1|max:100',
        ]);

        // Check if jadwal already exists for this doctor on this day
        $existingJadwal = $dokter->jadwalDokter()
            ->where('hari_praktik', $validated['hari_praktik'])
            ->where('is_deleted', false)
            ->first();

        if ($existingJadwal) {
            return back()->withInput()->with('error', 'Jadwal untuk hari ' . $validated['hari_praktik'] . ' sudah ada!');
        }

        try {
            $dokter->jadwalDokter()->create([
                'hari_praktik' => $validated['hari_praktik'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'max_pasien' => $validated['max_pasien'],
                'is_deleted' => false,
            ]);

            return redirect()->route('admin.dokter.show', $dokter->dokter_id)
                ->with('success', 'Jadwal dokter berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateJadwal(Request $request, string $dokterId, string $jadwalId)
    {
        $dokter = Dokter::findOrFail($dokterId);
        $jadwal = $dokter->jadwalDokter()->findOrFail($jadwalId);

        if ($dokter->is_deleted || $jadwal->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'hari_praktik' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'max_pasien' => 'required|integer|min:1|max:100',
        ]);

        // Check if jadwal already exists for this doctor on this day (excluding current jadwal)
        $existingJadwal = $dokter->jadwalDokter()
            ->where('hari_praktik', $validated['hari_praktik'])
            ->where('jadwal_id', '!=', $jadwalId)
            ->where('is_deleted', false)
            ->first();

        if ($existingJadwal) {
            return back()->withInput()->with('error', 'Jadwal untuk hari ' . $validated['hari_praktik'] . ' sudah ada!');
        }

        try {
            $jadwal->update([
                'hari_praktik' => $validated['hari_praktik'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'max_pasien' => $validated['max_pasien'],
            ]);

            return redirect()->route('admin.dokter.show', $dokter->dokter_id)
                ->with('success', 'Jadwal dokter berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyJadwal(string $dokterId, string $jadwalId)
    {
        $dokter = Dokter::findOrFail($dokterId);
        $jadwal = $dokter->jadwalDokter()->findOrFail($jadwalId);

        try {
            $jadwal->update(['is_deleted' => true]);

            return redirect()->route('admin.dokter.show', $dokter->dokter_id)
                ->with('success', 'Jadwal dokter berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
