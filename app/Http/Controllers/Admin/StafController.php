<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\User;
use App\Models\Staf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StafController extends Controller
{
    public function index(Request $request)
    {
        $query = Staf::where('is_deleted', false)->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip_rs', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('bagian')) {
            $query->where('bagian', $request->bagian);
        }

        $stafs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.staf.index', compact('stafs'));
    }

    public function create()
    {
        return view('admin.staf.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'nama_lengkap' => 'required|string|max:100',
            'nik' => 'required|string|size:16|unique:staf,nik',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'bagian' => 'required|in:pendaftaran,farmasi,laboratorium,kasir',
        ]);

        DB::beginTransaction();

        try {
            // Generate random password
            $password = Str::random(12);

            // Create user account
            $user = User::create([
                'email' => $validated['email'],
                'password' => $password,
                'role' => 'staf',
                'is_deleted' => false,
            ]);

            // Create staf record
            $staf = Staf::create([
                'user_id' => $user->user_id,
                'nip_rs' => $this->generateNipRS(),
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
                'bagian' => $validated['bagian'],
                'is_deleted' => false,
            ]);

            DB::commit();

            return redirect()->route('admin.staf.show', $staf->staf_id)
                ->with('success', 'Staf berhasil ditambahkan!')
                ->with('password', $password)
                ->with('email', $validated['email']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $staf = Staf::with('user')->findOrFail($id);

        if ($staf->is_deleted) {
            abort(404);
        }

        return view('admin.staf.show', compact('staf'));
    }

    public function edit(string $id)
    {
        $staf = Staf::with('user')->findOrFail($id);

        if ($staf->is_deleted) {
            abort(404);
        }

        return view('admin.staf.edit', compact('staf'));
    }

    public function update(Request $request, string $id)
    {
        $staf = Staf::with('user')->findOrFail($id);

        if ($staf->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($staf->user_id, 'user_id')],
            'nama_lengkap' => 'required|string|max:100',
            'nik' => ['required', 'string', 'size:16', Rule::unique('staf', 'nik')->ignore($id, 'staf_id')],
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'bagian' => 'required|in:pendaftaran,farmasi,laboratorium,kasir',
        ]);

        DB::beginTransaction();

        try {
            // Update user email
            $staf->user->update([
                'email' => $validated['email'],
            ]);

            // Update staf
            $staf->update([
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
                'bagian' => $validated['bagian'],
            ]);

            DB::commit();

            return redirect()->route('admin.staf.show', $staf->staf_id)
                ->with('success', 'Data staf berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $staf = Staf::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            $staf->update(['is_deleted' => true]);
            $staf->user->update(['is_deleted' => true]);

            DB::commit();

            return redirect()->route('admin.staf.index')
                ->with('success', 'Staf berhasil dihapus!');
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
