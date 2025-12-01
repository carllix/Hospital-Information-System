<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function dashboard(): View
    {
        return view('pasien.dashboard');
    }

    public function pembayaran(Request $request): View
    {
        $pasien = auth()->user()->pasien;

        $query = Tagihan::with(['detailTagihan', 'pembayaran', 'pendaftaran'])
            ->where('pasien_id', $pasien->pasien_id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by jenis tagihan
        if ($request->filled('jenis')) {
            $query->where('jenis_tagihan', $request->jenis);
        }

        $tagihans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate total unpaid bills
        $totalBelumBayar = Tagihan::where('pasien_id', $pasien->pasien_id)
            ->where('status', 'belum_bayar')
            ->sum('total_tagihan');

        return view('pasien.pembayaran', compact('tagihans', 'totalBelumBayar'));
    }

    public function rekamMedis(Request $request)
    {
        $pasien = auth()->user()->pasien;

        $query = Pemeriksaan::with(['dokter', 'pendaftaran', 'resep.detailResep', 'permintaanLab.hasilLab'])
            ->where('pasien_id', $pasien->pasien_id);

        // Filter by search (diagnosa atau nama dokter)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('diagnosa', 'like', "%{$search}%")
                    ->orWhereHas('dokter', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('status')) {
            $query->where('status_pasien', $request->status);
        }

        $query->orderBy('tanggal_pemeriksaan', 'desc');

        $pemeriksaan = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('pasien.components.rekam-medis-table', compact('pemeriksaan'))->render();
        }

        return view('pasien.rekam-medis', compact('pemeriksaan'));
    }

    public function healthMonitoring(): View
    {
        return view('pasien.health-monitoring');
    }

    public function profile(): View
    {
        $pasien = auth()->user()->pasien;
        return view('pasien.profile', compact('pasien'));
    }

    public function editProfile(): View
    {
        $pasien = auth()->user()->pasien;
        return view('pasien.edit-profile', compact('pasien'));
    }

    public function updateProfile(Request $request)
    {
        $pasien = auth()->user()->pasien;

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'provinsi' => 'nullable|string|max:100',
            'kota_kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kewarganegaraan' => 'nullable|string|max:50',
            'no_telepon' => 'required|string|max:15',
            'golongan_darah' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'wearable_device_id' => 'nullable|string|max:50',
        ]);

        if ($request->filled('wearable_device_id')) {
            $validDevices = ['DEVICE123', 'DEVICE456', 'DEVICE789'];

            if (!in_array($request->wearable_device_id, $validDevices)) {
                return back()->withErrors(['wearable_device_id' => 'Wearable device ID tidak ditemukan.'])->withInput();
            }
        }

        try {
            $pasien->update($validated);

            return redirect()->route('pasien.profile')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profil.'])->withInput();
        }
    }
}
