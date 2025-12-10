<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Tagihan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function dashboard(): View
    {
        $pasien = auth()->user()->pasien;

        // Jadwal Kunjungan Mendatang
        $jadwalKunjunganMendatang = Pendaftaran::where('pasien_id', $pasien->pasien_id)
            ->whereIn('status', ['menunggu', 'dipanggil', 'diperiksa'])
            ->where('tanggal_kunjungan', '>=', today())
            ->count();

        // Total Kunjungan
        $totalKunjungan = Pendaftaran::where('pasien_id', $pasien->pasien_id)->count();

        // Riwayat Pemeriksaan
        $riwayatPemeriksaan = Pemeriksaan::whereHas('pendaftaran', function ($q) use ($pasien) {
            $q->where('pasien_id', $pasien->pasien_id);
        })->count();

        // Total Tagihan Belum Bayar
        $totalBelumBayar = Tagihan::whereHas('pemeriksaan.pendaftaran', function ($q) use ($pasien) {
            $q->where('pasien_id', $pasien->pasien_id);
        })
            ->whereIn('status', ['belum_bayar', 'sebagian'])
            ->sum('total_tagihan');

        return view('pasien.dashboard', compact(
            'jadwalKunjunganMendatang',
            'totalKunjungan',
            'riwayatPemeriksaan',
            'totalBelumBayar'
        ));
    }

    public function pembayaran(Request $request): View
    {
        $pasien = auth()->user()->pasien;

        $query = Tagihan::with(['detailTagihan', 'pembayaran', 'pemeriksaan.pendaftaran'])
            ->whereHas('pemeriksaan.pendaftaran', function ($q) use ($pasien) {
                $q->where('pasien_id', $pasien->pasien_id);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihans = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalBelumBayar = Tagihan::whereHas('pemeriksaan.pendaftaran', function ($q) use ($pasien) {
            $q->where('pasien_id', $pasien->pasien_id);
        })
            ->where('status', 'belum_bayar')
            ->sum('total_tagihan');

        return view('pasien.pembayaran', compact('tagihans', 'totalBelumBayar'));
    }

    public function pembayaranDetail($id)
    {
        $pasien = auth()->user()->pasien;

        $tagihan = Tagihan::with(['detailTagihan', 'pembayaran', 'pemeriksaan.pendaftaran'])
            ->whereHas('pemeriksaan.pendaftaran', function ($q) use ($pasien) {
                $q->where('pasien_id', $pasien->pasien_id);
            })
            ->where('tagihan_id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'tagihan_id' => $tagihan->tagihan_id,
                'tanggal' => $tagihan->created_at->translatedFormat('j F Y H:i'),
                'nomor_antrian' => $tagihan->pemeriksaan->pendaftaran->nomor_antrian ?? null,
                'total_tagihan' => number_format($tagihan->total_tagihan, 0, ',', '.'),
                'status' => $tagihan->status,
                'detail_items' => $tagihan->detailTagihan->map(function ($detail) {
                    return [
                        'nama_item' => $detail->nama_item,
                        'jenis_item' => ucfirst($detail->jenis_item),
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

        $query = Pemeriksaan::with(['pendaftaran.jadwalDokter.dokter', 'resep.detailResep', 'permintaanLab.hasilLab'])
            ->whereHas('pendaftaran', function ($q) use ($pasien) {
                $q->where('pasien_id', $pasien->pasien_id);
            });

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(diagnosa) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('pendaftaran.jadwalDokter.dokter', function ($q) use ($search) {
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
            $query->where('status', $request->status);
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
            'pendaftaran.jadwalDokter.dokter',
            'pendaftaran',
            'resep.detailResep.obat',
            'permintaanLab.hasilLab',
            'rujukan'
        ])
            ->whereHas('pendaftaran', function ($q) use ($pasien) {
                $q->where('pasien_id', $pasien->pasien_id);
            })
            ->where('pemeriksaan_id', $id)
            ->firstOrFail();

        $dokter = $pemeriksaan->pendaftaran->jadwalDokter->dokter;

        return response()->json([
            'success' => true,
            'data' => [
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'tanggal_pemeriksaan' => $pemeriksaan->tanggal_pemeriksaan->translatedFormat('j F Y H:i'),
                'dokter' => [
                    'nama' => $dokter->nama_lengkap,
                    'spesialisasi' => $dokter->spesialisasi ?? '-',
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
                'status' => $pemeriksaan->status,
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
                    'tujuan_rujukan' => $pemeriksaan->rujukan->tujuan_rujukan,
                    'rs_tujuan' => $pemeriksaan->rujukan->rs_tujuan ?? '-',
                    'dokter_spesialis' => $pemeriksaan->rujukan->dokter_spesialis_tujuan ?? '-',
                    'alasan_rujukan' => $pemeriksaan->rujukan->alasan_rujukan,
                    'diagnosa_sementara' => $pemeriksaan->rujukan->diagnosa_sementara ?? '-',
                    'tanggal_rujukan' => $pemeriksaan->rujukan->tanggal_rujukan->translatedFormat('j F Y'),
                ] : null,
            ]
        ]);
    }

    public function healthMonitoring(): View
    {
        return view('pasien.health-monitoring');
    }

    public function pendaftaranKunjungan(): View
    {
        // Get list of unique specializations from active doctors
        $spesialisasiList = Dokter::where('is_deleted', false)
            ->distinct()
            ->orderBy('spesialisasi')
            ->pluck('spesialisasi');

        return view('pasien.pendaftaran-kunjungan', compact('spesialisasiList'));
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

    public function storePendaftaranKunjungan(Request $request): RedirectResponse
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_dokter,jadwal_id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'keluhan_utama' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $pasien = auth()->user()->pasien;
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
                return redirect()->route('pasien.pendaftaran-kunjungan')
                    ->with('error', "Kuota pendaftaran untuk jadwal ini sudah penuh (max: {$jadwal->max_pasien} pasien)");
            }

            // Generate queue number with format
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

            Pendaftaran::create([
                'pasien_id' => $pasien->pasien_id,
                'jadwal_id' => $request->jadwal_id,
                'tanggal_daftar' => now(),
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'nomor_antrian' => $nomorAntrian,
                'keluhan_utama' => $request->keluhan_utama,
                'staf_pendaftaran_id' => null, // Online registration
                'status' => 'menunggu',
            ]);

            DB::commit();
            return redirect()->route('pasien.jadwal-kunjungan')
                ->with('success', "Pendaftaran berhasil! Nomor Antrian: {$nomorAntrian} - Dr. {$jadwal->dokter->nama_lengkap} ({$jadwal->hari_praktik}, {$request->tanggal_kunjungan})");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pasien.pendaftaran-kunjungan')
                ->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
        }
    }

    public function jadwalKunjungan(): View
    {
        $pasien = auth()->user()->pasien;

        // Get upcoming appointments (today and future, not completed/cancelled)
        $jadwalKunjungan = Pendaftaran::with(['jadwalDokter.dokter', 'pemeriksaan'])
            ->where('pasien_id', $pasien->pasien_id)
            ->whereDate('tanggal_kunjungan', '>=', today())
            ->whereIn('status', ['menunggu', 'dipanggil', 'diperiksa'])
            ->orderBy('tanggal_kunjungan', 'asc')
            ->orderBy('tanggal_daftar', 'asc')
            ->get();

        return view('pasien.jadwal-kunjungan', compact('jadwalKunjungan'));
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

        $routeName = 'pasien.jadwal-dokter';

        return view('pendaftaran.jadwal-dokter', compact('dokters', 'spesialisasiList', 'routeName'));
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
