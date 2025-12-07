<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pemeriksaan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_tagihan', $request->jenis);
        }

        $tagihans = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalBelumBayar = Tagihan::where('pasien_id', $pasien->pasien_id)
            ->where('status', 'belum_bayar')
            ->sum('total_tagihan');

        return view('pasien.pembayaran', compact('tagihans', 'totalBelumBayar'));
    }

    public function pembayaranDetail($id)
    {
        $pasien = auth()->user()->pasien;

        $tagihan = Tagihan::with(['detailTagihan', 'pembayaran', 'pendaftaran'])
            ->where('pasien_id', $pasien->pasien_id)
            ->where('tagihan_id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'tagihan_id' => $tagihan->tagihan_id,
                'tanggal' => $tagihan->created_at->translatedFormat('j F Y H:i'),
                'jenis_tagihan' => ucfirst($tagihan->jenis_tagihan),
                'nomor_antrian' => $tagihan->pendaftaran->nomor_antrian ?? null,
                'total_tagihan' => number_format($tagihan->total_tagihan, 0, ',', '.'),
                'status' => $tagihan->status,
                'detail_items' => $tagihan->detailTagihan->map(function ($detail) {
                    return [
                        'nama_item' => $detail->nama_item,
                        'jumlah' => $detail->jumlah,
                        'harga_satuan' => number_format($detail->harga_satuan, 0, ',', '.'),
                        'subtotal' => number_format($detail->subtotal, 0, ',', '.'),
                    ];
                }),
                'pembayaran' => $tagihan->pembayaran ? [
                    'tanggal_bayar' => $tagihan->pembayaran->tanggal_bayar->translatedFormat('j F Y H:i'),
                    'metode_pembayaran' => ucfirst($tagihan->pembayaran->metode_pembayaran),
                    'no_kwitansi' => $tagihan->pembayaran->no_kwitansi,
                    'jumlah_bayar' => number_format($tagihan->pembayaran->jumlah_bayar, 0, ',', '.'),
                ] : null,
            ]
        ]);
    }

    public function rekamMedis(Request $request)
    {
        $pasien = auth()->user()->pasien;

        $query = Pemeriksaan::with(['dokter', 'pendaftaran', 'resep.detailResep', 'permintaanLab.hasilLab'])
            ->where('pasien_id', $pasien->pasien_id);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(diagnosa) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('dokter', function ($q) use ($search) {
                        $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"]);
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

    public function rekamMedisDetail($id)
    {
        $pasien = auth()->user()->pasien;

        $pemeriksaan = Pemeriksaan::with([
            'dokter',
            'pendaftaran',
            'resep.detailResep.obat',
            'permintaanLab.hasilLab.petugasLab',
            'rujukan.dokterPerujuk'
        ])
            ->where('pasien_id', $pasien->pasien_id)
            ->where('pemeriksaan_id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'tanggal_pemeriksaan' => $pemeriksaan->tanggal_pemeriksaan->translatedFormat('j F Y H:i'),
                'dokter' => [
                    'nama' => $pemeriksaan->dokter->nama_lengkap,
                    'spesialisasi' => $pemeriksaan->dokter->spesialisasi ?? '-',
                ],
                'keluhan' => $pemeriksaan->pendaftaran->keluhan_utama ?? '-',
                'anamnesa' => $pemeriksaan->anamnesa ?? '-',
                'pemeriksaan_fisik' => $pemeriksaan->pemeriksaan_fisik ?? '-',
                'vital_signs' => [
                    'tekanan_darah' => $pemeriksaan->tekanan_darah ?? '-',
                    'suhu_tubuh' => $pemeriksaan->suhu_tubuh ? $pemeriksaan->suhu_tubuh . '°C' : '-',
                    'berat_badan' => $pemeriksaan->berat_badan ? $pemeriksaan->berat_badan . ' kg' : '-',
                    'tinggi_badan' => $pemeriksaan->tinggi_badan ? $pemeriksaan->tinggi_badan . ' cm' : '-',
                ],
                'diagnosa' => $pemeriksaan->diagnosa ?? '-',
                'icd10_code' => $pemeriksaan->icd10_code ?? '-',
                'tindakan_medis' => $pemeriksaan->tindakan_medis ?? '-',
                'catatan_dokter' => $pemeriksaan->catatan_dokter ?? '-',
                'status_pasien' => $pemeriksaan->status_pasien,
                'resep' => $pemeriksaan->resep ? [
                    'tanggal_resep' => $pemeriksaan->resep->tanggal_resep->translatedFormat('j F Y'),
                    'status' => $pemeriksaan->resep->status,
                    'obat' => $pemeriksaan->resep->detailResep->map(function ($detail) {
                        return [
                            'nama_obat' => $detail->obat->nama_obat,
                            'jumlah' => $detail->jumlah,
                            'dosis' => $detail->dosis,
                            'aturan_pakai' => $detail->aturan_pakai,
                        ];
                    })
                ] : null,
                'lab' => $pemeriksaan->permintaanLab->map(function ($lab) {
                    return [
                        'jenis_pemeriksaan' => str_replace('_', ' ', ucwords($lab->jenis_pemeriksaan, '_')),
                        'tanggal_permintaan' => $lab->tanggal_permintaan->translatedFormat('j F Y'),
                        'status' => $lab->status,
                        'hasil' => $lab->hasilLab->map(function ($hasil) {
                            return [
                                'jenis_test' => $hasil->jenis_test,
                                'parameter' => $hasil->parameter,
                                'hasil' => $hasil->hasil,
                                'satuan' => $hasil->satuan ?? '',
                                'nilai_normal' => $hasil->nilai_normal ?? '-',
                                'tanggal_hasil' => $hasil->tanggal_hasil->translatedFormat('j F Y'),
                            ];
                        })
                    ];
                }),
                'rujukan' => $pemeriksaan->rujukan ? [
                    'rs_tujuan' => $pemeriksaan->rujukan->rs_tujuan ?? '-',
                    'dokter_spesialis' => $pemeriksaan->rujukan->dokter_spesialis_tujuan ?? '-',
                    'alasan_rujukan' => $pemeriksaan->rujukan->alasan_rujukan,
                    'diagnosa_sementara' => $pemeriksaan->rujukan->diagnosa_sementara ?? '-',
                    'tanggal_rujukan' => $pemeriksaan->rujukan->tanggal_rujukan->translatedFormat('j F Y'),
                    'dokter_perujuk' => $pemeriksaan->rujukan->dokterPerujuk->nama_lengkap,
                ] : null,
            ]
        ]);
    }

    public function healthMonitoring(): View
    {
        return view('pasien.health-monitoring');
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
            'tempat_lahir' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kewarganegaraan' => 'nullable|string|max:50',
            'no_telepon' => 'required|string|max:15',
            'golongan_darah' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'wearable_device_id' => 'nullable|string|max:50',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('wearable_device_id')) {
            $validDevices = ['DEVICE123', 'DEVICE456', 'DEVICE789'];

            if (!in_array($request->wearable_device_id, $validDevices)) {
                return back()->withErrors(['wearable_device_id' => 'Wearable device ID tidak ditemukan.'])->withInput();
            }
        }

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
            $pasien->update($validated);

            $message = 'Profil berhasil diperbarui.';
            if ($request->filled('new_password')) {
                $message = 'Profil dan password berhasil diperbarui.';
            }

            return redirect()->route('pasien.profile')->with('success', $message);
        } catch (\Exception) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profil.'])->withInput();
        }
    }
}
