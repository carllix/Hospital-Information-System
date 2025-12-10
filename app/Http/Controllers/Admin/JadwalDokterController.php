<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalDokterController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalDokter::where('is_deleted', false)->with('dokter');

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereHas('dokter', function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('hari_praktik')) {
            $query->where('hari_praktik', $request->hari_praktik);
        }

        if ($request->filled('dokter_id')) {
            $query->where('dokter_id', $request->dokter_id);
        }

        $jadwals = $query->orderBy('dokter_id')->orderBy('hari_praktik')->paginate(10);

        $dokters = Dokter::where('is_deleted', false)
            ->orderBy('nama_lengkap')
            ->get(['dokter_id', 'nama_lengkap']);

        return view('admin.jadwal-dokter.index', compact('jadwals', 'dokters'));
    }

    public function create()
    {
        $dokters = Dokter::where('is_deleted', false)
            ->orderBy('nama_lengkap')
            ->get(['dokter_id', 'nama_lengkap', 'spesialisasi']);

        return view('admin.jadwal-dokter.create', compact('dokters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokter,dokter_id',
            'hari_praktik' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'max_pasien' => 'required|integer|min:1',
        ]);

        try {
            $jadwal = JadwalDokter::create([
                'dokter_id' => $validated['dokter_id'],
                'hari_praktik' => $validated['hari_praktik'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'max_pasien' => $validated['max_pasien'],
                'is_deleted' => false,
            ]);

            return redirect()->route('admin.jadwal-dokter.show', $jadwal->jadwal_id)
                ->with('success', 'Jadwal dokter berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $jadwal = JadwalDokter::with('dokter')->findOrFail($id);

        if ($jadwal->is_deleted) {
            abort(404);
        }

        return view('admin.jadwal-dokter.show', compact('jadwal'));
    }

    public function edit(string $id)
    {
        $jadwal = JadwalDokter::with('dokter')->findOrFail($id);

        if ($jadwal->is_deleted) {
            abort(404);
        }

        $dokters = Dokter::where('is_deleted', false)
            ->orderBy('nama_lengkap')
            ->get(['dokter_id', 'nama_lengkap', 'spesialisasi']);

        return view('admin.jadwal-dokter.edit', compact('jadwal', 'dokters'));
    }

    public function update(Request $request, string $id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        if ($jadwal->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokter,dokter_id',
            'hari_praktik' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'max_pasien' => 'required|integer|min:1',
        ]);

        try {
            $jadwal->update([
                'dokter_id' => $validated['dokter_id'],
                'hari_praktik' => $validated['hari_praktik'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'max_pasien' => $validated['max_pasien'],
            ]);

            return redirect()->route('admin.jadwal-dokter.show', $jadwal->jadwal_id)
                ->with('success', 'Jadwal dokter berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        try {
            $jadwal->update(['is_deleted' => true]);

            return redirect()->route('admin.jadwal-dokter.index')
                ->with('success', 'Jadwal dokter berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
