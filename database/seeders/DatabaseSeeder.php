<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Staf;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Layanan;
use App\Models\Obat;
use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;
use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\PermintaanLab;
use App\Models\HasilLab;
use App\Models\Rujukan;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use App\Models\Pembayaran;
use App\Models\WearableData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting database seeding...');

        // Create Admin
        $this->command->info('Creating Admin...');
        $this->createAdmin();

        // Create Pasien
        $this->command->info('Creating Pasien...');
        $this->createPasien();

        // Create Staf
        $this->command->info('Creating Staf...');
        $this->createStaf();

        // Create Dokter & Jadwal
        $this->command->info('Creating Dokter and Jadwal...');
        $this->createDokterAndJadwal();

        // Create Layanan
        $this->command->info('Creating Layanan...');
        $this->createLayanan();

        // Create Obat
        $this->command->info('Creating Obat...');
        $this->createObat();

        // Create Pendaftaran
        $this->command->info('Creating Pendaftaran...');
        $this->createPendaftaran();

        // Create Pemeriksaan
        $this->command->info('Creating Pemeriksaan...');
        $this->createPemeriksaan();

        // Create Resep
        $this->command->info('Creating Resep and Detail Resep...');
        $this->createResep();

        // Create Permintaan Lab
        $this->command->info('Creating Permintaan Lab and Hasil Lab...');
        $this->createPermintaanLab();

        // Create Rujukan
        $this->command->info('Creating Rujukan...');
        $this->createRujukan();

        // Create Tagihan and Pembayaran
        $this->command->info('Creating Tagihan and Pembayaran...');
        $this->createTagihanPembayaran();

        // Create Wearable Data
        $this->command->info('Creating Wearable Data...');
        $this->createWearableData();

        $this->command->info('Database seeding completed successfully!');
    }

    private function createAdmin(): void
    {
        User::create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }

    private function createPasien(): void
    {
        $pasienData = [
            [
                'email' => 'pasien1@test.com',
                'nama' => 'Budi Santoso',
                'nik' => '3201012345670001',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1990-05-15',
                'tempat_lahir' => 'Bandung',
                'no_telepon' => '081234567801',
                'alamat' => 'Jl. Merdeka No. 123',
                'kota' => 'Bandung',
                'kecamatan' => 'Coblong',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'A+',
            ],
            [
                'email' => 'pasien2@test.com',
                'nama' => 'Siti Nurhaliza',
                'nik' => '3201012345670002',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1985-08-20',
                'tempat_lahir' => 'Jakarta',
                'no_telepon' => '081234567802',
                'alamat' => 'Jl. Gatot Subroto No. 45',
                'kota' => 'Bandung',
                'kecamatan' => 'Sukajadi',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'B+',
            ],
            [
                'email' => 'pasien3@test.com',
                'nama' => 'Ahmad Wijaya',
                'nik' => '3201012345670003',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1995-03-10',
                'tempat_lahir' => 'Surabaya',
                'no_telepon' => '081234567803',
                'alamat' => 'Jl. Asia Afrika No. 78',
                'kota' => 'Bandung',
                'kecamatan' => 'Sumur Bandung',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'O+',
            ],
            [
                'email' => 'pasien4@test.com',
                'nama' => 'Dewi Lestari',
                'nik' => '3201012345670004',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1992-11-25',
                'tempat_lahir' => 'Bandung',
                'no_telepon' => '081234567804',
                'alamat' => 'Jl. Dago No. 234',
                'kota' => 'Bandung',
                'kecamatan' => 'Dago',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'AB+',
            ],
            [
                'email' => 'pasien5@test.com',
                'nama' => 'Rudi Hermawan',
                'nik' => '3201012345670005',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1988-07-18',
                'tempat_lahir' => 'Yogyakarta',
                'no_telepon' => '081234567805',
                'alamat' => 'Jl. Braga No. 56',
                'kota' => 'Bandung',
                'kecamatan' => 'Braga',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'A-',
            ],
        ];

        foreach ($pasienData as $index => $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'pasien',
            ]);

            $today = now()->format('Ymd');
            $noRM = 'RM-' . $today . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            Pasien::create([
                'user_id' => $user->user_id,
                'no_rekam_medis' => $noRM,
                'nama_lengkap' => $data['nama'],
                'nik' => $data['nik'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'tempat_lahir' => $data['tempat_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'kota_kabupaten' => $data['kota'],
                'kecamatan' => $data['kecamatan'],
                'provinsi' => $data['provinsi'],
                'no_telepon' => $data['no_telepon'],
                'golongan_darah' => $data['golongan_darah'],
            ]);
        }
    }

    private function createStaf(): void
    {
        $stafData = [
            [
                'email' => 'pendaftaran1@test.com',
                'nama' => 'Linda Kartika',
                'nik' => '3201012345670101',
                'nip_rs' => 'NIPRS00001',
                'bagian' => 'pendaftaran',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1987-04-12',
                'tempat_lahir' => 'Bandung',
                'no_telepon' => '081234567901',
            ],
            [
                'email' => 'kasir1@test.com',
                'nama' => 'Maya Sari',
                'nik' => '3201012345670103',
                'nip_rs' => 'NIPRS00003',
                'bagian' => 'kasir',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1989-06-15',
                'tempat_lahir' => 'Jakarta',
                'no_telepon' => '081234567903',
            ],
            [
                'email' => 'apoteker1@test.com',
                'nama' => 'Rizki Firmansyah',
                'nik' => '3201012345670104',
                'nip_rs' => 'NIPRS00004',
                'bagian' => 'farmasi',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1991-12-08',
                'tempat_lahir' => 'Bandung',
                'no_telepon' => '081234567904',
            ],
            [
                'email' => 'lab1@test.com',
                'nama' => 'Ratna Dewi',
                'nik' => '3201012345670105',
                'nip_rs' => 'NIPRS00005',
                'bagian' => 'laboratorium',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1988-03-30',
                'tempat_lahir' => 'Bogor',
                'no_telepon' => '081234567905',
            ],
        ];

        foreach ($stafData as $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'staf',
            ]);

            Staf::create([
                'user_id' => $user->user_id,
                'nip_rs' => $data['nip_rs'],
                'nama_lengkap' => $data['nama'],
                'nik' => $data['nik'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'tempat_lahir' => $data['tempat_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => 'Jl. Sudirman No. 123, Bandung',
                'kota_kabupaten' => 'Bandung',
                'kecamatan' => 'Cidadap',
                'provinsi' => 'Jawa Barat',
                'no_telepon' => $data['no_telepon'],
                'bagian' => $data['bagian'],
            ]);
        }
    }

    private function createDokterAndJadwal(): void
    {
        $dokterData = [
            [
                'email' => 'dr.andi@test.com',
                'nama' => 'dr. Andi Kusuma, Sp.PD',
                'nik' => '3201012345670201',
                'nip_rs' => 'NIPRS00006',
                'tanggal_lahir' => '1978-03-15',
                'tempat_lahir' => 'Jakarta',
                'jenis_kelamin' => 'Laki-Laki',
                'spesialisasi' => 'Penyakit Dalam',
                'no_str' => 'STR-PD-001-2020',
                'jadwal' => [
                    ['hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'max_pasien' => 20],
                    ['hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'max_pasien' => 20],
                    ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'max_pasien' => 20],
                ],
            ],
            [
                'email' => 'dr.sari@test.com',
                'nama' => 'dr. Sari Wijayanti, Sp.A',
                'nik' => '3201012345670202',
                'nip_rs' => 'NIPRS00007',
                'tanggal_lahir' => '1982-07-22',
                'tempat_lahir' => 'Bandung',
                'jenis_kelamin' => 'Perempuan',
                'spesialisasi' => 'Anak',
                'no_str' => 'STR-PA-002-2020',
                'jadwal' => [
                    ['hari' => 'Selasa', 'jam_mulai' => '09:00', 'jam_selesai' => '14:00', 'max_pasien' => 25],
                    ['hari' => 'Kamis', 'jam_mulai' => '09:00', 'jam_selesai' => '14:00', 'max_pasien' => 25],
                    ['hari' => 'Sabtu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'max_pasien' => 15],
                ],
            ],
            [
                'email' => 'dr.budi@test.com',
                'nama' => 'dr. Budi Santoso, Sp.OG',
                'nik' => '3201012345670203',
                'nip_rs' => 'NIPRS00008',
                'tanggal_lahir' => '1980-11-05',
                'tempat_lahir' => 'Surabaya',
                'jenis_kelamin' => 'Laki-Laki',
                'spesialisasi' => 'Kandungan',
                'no_str' => 'STR-OG-003-2020',
                'jadwal' => [
                    ['hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'max_pasien' => 15],
                    ['hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'max_pasien' => 15],
                ],
            ],
        ];

        foreach ($dokterData as $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'dokter',
            ]);

            $dokter = Dokter::create([
                'user_id' => $user->user_id,
                'nip_rs' => $data['nip_rs'],
                'nama_lengkap' => $data['nama'],
                'nik' => $data['nik'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'tempat_lahir' => $data['tempat_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => 'Jl. Dokter No. 456, Bandung',
                'kota_kabupaten' => 'Bandung',
                'kecamatan' => 'Bandung Wetan',
                'provinsi' => 'Jawa Barat',
                'no_telepon' => '081298765' . substr($data['nip_rs'], -3),
                'spesialisasi' => $data['spesialisasi'],
                'no_str' => $data['no_str'],
            ]);

            // Create jadwal for this dokter
            foreach ($data['jadwal'] as $jadwal) {
                JadwalDokter::create([
                    'dokter_id' => $dokter->dokter_id,
                    'hari_praktik' => $jadwal['hari'],
                    'waktu_mulai' => $jadwal['jam_mulai'],
                    'waktu_selesai' => $jadwal['jam_selesai'],
                    'max_pasien' => $jadwal['max_pasien'],
                ]);
            }
        }
    }

    private function createLayanan(): void
    {
        $layananData = [
            ['kode_layanan' => 'LYN-001', 'nama_layanan' => 'Konsultasi Dokter Umum', 'kategori' => 'konsultasi', 'harga' => 100000],
            ['kode_layanan' => 'LYN-002', 'nama_layanan' => 'Konsultasi Dokter Spesialis', 'kategori' => 'konsultasi', 'harga' => 200000],
            ['kode_layanan' => 'LYN-003', 'nama_layanan' => 'Tindakan Jahit Luka', 'kategori' => 'tindakan', 'harga' => 150000],
            ['kode_layanan' => 'LYN-004', 'nama_layanan' => 'Tindakan Cabut Gigi', 'kategori' => 'tindakan', 'harga' => 120000],
            ['kode_layanan' => 'LYN-005', 'nama_layanan' => 'Tindakan Infus', 'kategori' => 'tindakan', 'harga' => 80000],
        ];

        foreach ($layananData as $layanan) {
            Layanan::create($layanan);
        }
    }

    private function createObat(): void
    {
        $obatData = [
            ['kode_obat' => 'OBT-001', 'nama_obat' => 'Paracetamol 500mg', 'kategori' => 'tablet', 'stok' => 1000, 'stok_minimum' => 100, 'harga' => 5000, 'satuan' => 'tablet', 'deskripsi' => 'Obat pereda demam dan nyeri'],
            ['kode_obat' => 'OBT-002', 'nama_obat' => 'Amoxicillin 500mg', 'kategori' => 'kapsul', 'stok' => 500, 'stok_minimum' => 50, 'harga' => 10000, 'satuan' => 'kapsul', 'deskripsi' => 'Antibiotik untuk infeksi bakteri'],
            ['kode_obat' => 'OBT-003', 'nama_obat' => 'OBH Combi', 'kategori' => 'sirup', 'stok' => 300, 'stok_minimum' => 30, 'harga' => 15000, 'satuan' => 'botol', 'deskripsi' => 'Obat batuk berdahak'],
            ['kode_obat' => 'OBT-004', 'nama_obat' => 'Antasida DOEN', 'kategori' => 'tablet', 'stok' => 400, 'stok_minimum' => 40, 'harga' => 8000, 'satuan' => 'tablet', 'deskripsi' => 'Obat maag dan gangguan pencernaan'],
            ['kode_obat' => 'OBT-005', 'nama_obat' => 'Ibuprofen 400mg', 'kategori' => 'tablet', 'stok' => 600, 'stok_minimum' => 60, 'harga' => 7000, 'satuan' => 'tablet', 'deskripsi' => 'Obat anti inflamasi dan pereda nyeri'],
            ['kode_obat' => 'OBT-006', 'nama_obat' => 'Cetirizine 10mg', 'kategori' => 'tablet', 'stok' => 350, 'stok_minimum' => 35, 'harga' => 6000, 'satuan' => 'tablet', 'deskripsi' => 'Obat anti alergi'],
            ['kode_obat' => 'OBT-007', 'nama_obat' => 'Vitamin C 1000mg', 'kategori' => 'tablet', 'stok' => 800, 'stok_minimum' => 80, 'harga' => 12000, 'satuan' => 'tablet', 'deskripsi' => 'Suplemen vitamin C'],
            ['kode_obat' => 'OBT-008', 'nama_obat' => 'Omeprazole 20mg', 'kategori' => 'kapsul', 'stok' => 250, 'stok_minimum' => 25, 'harga' => 15000, 'satuan' => 'kapsul', 'deskripsi' => 'Obat untuk mengurangi asam lambung'],
        ];

        foreach ($obatData as $obat) {
            Obat::create($obat);
        }
    }

    private function createPendaftaran(): void
    {
        $pasiens = Pasien::all();
        $jadwalDokters = JadwalDokter::with('dokter')->get();
        $stafPendaftaran = Staf::where('bagian', 'pendaftaran')->first();

        // Track antrian counter per dokter per tanggal
        $antrianCounters = [];

        // Create today's pendaftaran
        foreach ($pasiens->take(3) as $index => $pasien) {
            $jadwal = $jadwalDokters->random();
            $tanggalKunjungan = now()->toDateString();
            $nomorAntrian = $this->generateNomorAntrian($jadwal->dokter, $tanggalKunjungan, $antrianCounters);

            Pendaftaran::create([
                'pasien_id' => $pasien->pasien_id,
                'jadwal_id' => $jadwal->jadwal_id,
                'tanggal_daftar' => now()->subHours(3 - $index),
                'tanggal_kunjungan' => $tanggalKunjungan,
                'nomor_antrian' => $nomorAntrian,
                'keluhan_utama' => $this->getRandomKeluhan(),
                'staf_pendaftaran_id' => $stafPendaftaran?->staf_id,
                'status' => $index === 0 ? 'dipanggil' : 'menunggu',
            ]);
        }

        // Create past pendaftaran (selesai)
        foreach ($pasiens as $pasien) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                $jadwal = $jadwalDokters->random();
                $daysAgo = rand(1, 30);
                $tanggalKunjungan = now()->subDays($daysAgo)->toDateString();
                $nomorAntrian = $this->generateNomorAntrian($jadwal->dokter, $tanggalKunjungan, $antrianCounters);

                Pendaftaran::create([
                    'pasien_id' => $pasien->pasien_id,
                    'jadwal_id' => $jadwal->jadwal_id,
                    'tanggal_daftar' => now()->subDays($daysAgo),
                    'tanggal_kunjungan' => $tanggalKunjungan,
                    'nomor_antrian' => $nomorAntrian,
                    'keluhan_utama' => $this->getRandomKeluhan(),
                    'staf_pendaftaran_id' => $stafPendaftaran?->staf_id,
                    'status' => 'selesai',
                ]);
            }
        }
    }

    private function createPemeriksaan(): void
    {
        $pendaftarans = Pendaftaran::where('status', 'selesai')->get();

        $diagnosaList = [
            'ISPA (Infeksi Saluran Pernapasan Akut)',
            'Gastritis',
            'Hipertensi Grade 1',
            'Diabetes Mellitus Tipe 2',
            'Demam Typhoid',
            'Dermatitis',
            'Migrain',
            'Dispepsia',
            'Vertigo',
            'Osteoarthritis',
        ];

        $count = min(15, $pendaftarans->count());
        foreach ($pendaftarans->random($count) as $pendaftaran) {
            Pemeriksaan::create([
                'pendaftaran_id' => $pendaftaran->pendaftaran_id,
                'tanggal_pemeriksaan' => $pendaftaran->tanggal_daftar,
                'anamnesa' => $pendaftaran->keluhan_utama,
                'pemeriksaan_fisik' => 'Pemeriksaan fisik lengkap',
                'diagnosa' => $diagnosaList[array_rand($diagnosaList)],
                'tindakan_medis' => 'Pemeriksaan fisik dan anamnesa',
                'catatan_dokter' => 'Pasien dianjurkan istirahat cukup dan minum obat teratur',
                'tekanan_darah' => rand(110, 140) . '/' . rand(70, 90),
                'suhu_tubuh' => rand(36, 38) + (rand(0, 9) / 10),
                'berat_badan' => rand(50, 90),
                'tinggi_badan' => rand(150, 180),
                'status' => 'selesai',
            ]);
        }
    }

    private function createResep(): void
    {
        $pemeriksaans = Pemeriksaan::all();
        $obats = Obat::all();
        $apoteker = Staf::where('bagian', 'farmasi')->first();

        $count = min(10, $pemeriksaans->count());
        foreach ($pemeriksaans->random($count) as $pemeriksaan) {
            $resep = Resep::create([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'tanggal_resep' => $pemeriksaan->tanggal_pemeriksaan,
                'apoteker_id' => $apoteker?->staf_id,
                'status' => collect(['diproses', 'selesai'])->random(),
                'catatan_apoteker' => 'Obat sudah disiapkan',
            ]);

            // Add 2-3 obat per resep
            $selectedObats = $obats->random(rand(2, 3));
            foreach ($selectedObats as $obat) {
                DetailResep::create([
                    'resep_id' => $resep->resep_id,
                    'obat_id' => $obat->obat_id,
                    'jumlah' => rand(10, 30),
                    'dosis' => rand(1, 3) . 'x sehari',
                    'aturan_pakai' => 'Diminum setelah makan',
                ]);
            }
        }
    }

    private function createPermintaanLab(): void
    {
        $pemeriksaans = Pemeriksaan::all();
        $labStaf = Staf::where('bagian', 'laboratorium')->first();

        $jenisLab = ['darah_lengkap', 'urine', 'gula_darah', 'kolesterol', 'radiologi'];

        $count = min(8, $pemeriksaans->count());
        foreach ($pemeriksaans->random($count) as $pemeriksaan) {
            $jenisPemeriksaan = $jenisLab[array_rand($jenisLab)];
            $status = collect(['menunggu', 'diproses', 'selesai'])->random();

            $permintaan = PermintaanLab::create([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'tanggal_permintaan' => $pemeriksaan->tanggal_pemeriksaan,
                'jenis_pemeriksaan' => $jenisPemeriksaan,
                'catatan_permintaan' => 'Pemeriksaan rutin',
                'status' => $status,
                'petugas_lab_id' => $labStaf?->staf_id,
            ]);

            // Create hasil lab for completed requests
            if ($status === 'selesai') {
                $labResults = [
                    'darah_lengkap' => ['jenis' => 'Darah Lengkap', 'parameter' => 'Hemoglobin', 'hasil' => '14.5', 'satuan' => 'g/dL', 'normal' => '13-17 g/dL'],
                    'urine' => ['jenis' => 'Urine', 'parameter' => 'Protein', 'hasil' => 'Negatif', 'satuan' => null, 'normal' => 'Negatif'],
                    'gula_darah' => ['jenis' => 'Gula Darah', 'parameter' => 'Glukosa Puasa', 'hasil' => '95', 'satuan' => 'mg/dL', 'normal' => '70-100 mg/dL'],
                    'kolesterol' => ['jenis' => 'Kolesterol', 'parameter' => 'Kolesterol Total', 'hasil' => '180', 'satuan' => 'mg/dL', 'normal' => '<200 mg/dL'],
                    'radiologi' => ['jenis' => 'Radiologi', 'parameter' => 'Rontgen Thorax', 'hasil' => 'Normal', 'satuan' => null, 'normal' => 'Tidak ada kelainan'],
                ];

                $labData = $labResults[$jenisPemeriksaan];

                HasilLab::create([
                    'permintaan_lab_id' => $permintaan->permintaan_lab_id,
                    'jenis_test' => $labData['jenis'],
                    'parameter' => $labData['parameter'],
                    'hasil' => $labData['hasil'],
                    'satuan' => $labData['satuan'],
                    'nilai_normal' => $labData['normal'],
                    'keterangan' => 'Tidak ada kelainan yang ditemukan',
                    'tanggal_hasil' => now()->subDays(rand(1, 5)),
                    'petugas_lab_id' => $labStaf->staf_id,
                ]);
            }
        }
    }

    private function createRujukan(): void
    {
        $pemeriksaans = Pemeriksaan::limit(3)->get();

        $rsRujukan = [
            'RS Hasan Sadikin Bandung',
            'RS Advent Bandung',
            'RS Santo Borromeus',
            'RS Immanuel Bandung',
        ];

        foreach ($pemeriksaans as $pemeriksaan) {
            Rujukan::create([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'tujuan_rujukan' => 'Pemeriksaan Lanjutan',
                'rs_tujuan' => $rsRujukan[array_rand($rsRujukan)],
                'dokter_spesialis_tujuan' => 'Dokter Spesialis Penyakit Dalam',
                'tanggal_rujukan' => $pemeriksaan->tanggal_pemeriksaan,
                'alasan_rujukan' => 'Memerlukan pemeriksaan lanjutan dan penanganan spesialis',
                'diagnosa_sementara' => $pemeriksaan->diagnosa,
            ]);
        }
    }

    private function createTagihanPembayaran(): void
    {
        $pemeriksaans = Pemeriksaan::all();
        $layanan = Layanan::where('kode_layanan', 'LYN-002')->first();
        $kasir = Staf::where('bagian', 'kasir')->first();
        $kwitansiCounter = 1;

        foreach ($pemeriksaans as $pemeriksaan) {
            $totalTagihan = 0;

            // Create tagihan
            $tagihan = Tagihan::create([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'total_tagihan' => 0, // Will update later
                'status' => collect(['belum_bayar', 'lunas'])->random(),
            ]);

            // Add layanan (consultation)
            if ($layanan) {
                DetailTagihan::create([
                    'tagihan_id' => $tagihan->tagihan_id,
                    'layanan_id' => $layanan->layanan_id,
                    'jenis_item' => 'konsultasi',
                    'nama_item' => $layanan->nama_layanan,
                    'jumlah' => 1,
                    'harga_satuan' => $layanan->harga,
                    'subtotal' => $layanan->harga,
                ]);
                $totalTagihan += $layanan->harga;
            }

            // Add obat if resep exists
            $resep = Resep::where('pemeriksaan_id', $pemeriksaan->pemeriksaan_id)->first();
            if ($resep) {
                foreach ($resep->detailResep as $detail) {
                    $subtotal = $detail->jumlah * $detail->obat->harga;
                    DetailTagihan::create([
                        'tagihan_id' => $tagihan->tagihan_id,
                        'detail_resep_id' => $detail->detail_resep_id,
                        'jenis_item' => 'obat',
                        'nama_item' => $detail->obat->nama_obat,
                        'jumlah' => $detail->jumlah,
                        'harga_satuan' => $detail->obat->harga,
                        'subtotal' => $subtotal,
                    ]);
                    $totalTagihan += $subtotal;
                }
            }

            // Update total tagihan
            $tagihan->update(['total_tagihan' => $totalTagihan]);

            // Create pembayaran if status is lunas
            if ($tagihan->status === 'lunas' && $kasir) {
                Pembayaran::create([
                    'tagihan_id' => $tagihan->tagihan_id,
                    'tanggal_bayar' => $tagihan->created_at,
                    'metode_pembayaran' => collect(['tunai', 'debit', 'transfer', 'qris'])->random(),
                    'jumlah_bayar' => $totalTagihan,
                    'kasir_id' => $kasir->staf_id,
                    'no_kwitansi' => 'KWT-' . now()->format('Ymd') . '-' . str_pad($kwitansiCounter++, 4, '0', STR_PAD_LEFT),
                ]);
            }
        }
    }

    private function createWearableData(): void
    {
        // Assign wearable devices to first 2 patients
        Pasien::find(1)?->update(['wearable_device_id' => 'DEVICE123']);
        Pasien::find(2)?->update(['wearable_device_id' => 'DEVICE456']);

        $pasiens = Pasien::whereNotNull('wearable_device_id')->get();

        foreach ($pasiens as $pasien) {
            // Create data for last 7 days
            for ($i = 0; $i < 7; $i++) {
                WearableData::create([
                    'pasien_id' => $pasien->pasien_id,
                    'device_id' => $pasien->wearable_device_id,
                    'timestamp' => now()->subDays($i)->setHour(rand(8, 20)),
                    'heart_rate' => rand(60, 100),
                    'oxygen_saturation' => rand(95, 100),
                ]);
            }
        }
    }

    private function getRandomKeluhan(): string
    {
        $keluhans = [
            'Demam tinggi sejak 3 hari yang lalu',
            'Batuk dan pilek tidak kunjung sembuh',
            'Nyeri pada perut bagian bawah',
            'Sakit kepala berkepanjangan',
            'Sesak napas',
            'Nyeri dada sebelah kiri',
            'Pusing dan mual',
            'Diare sejak kemarin',
            'Nyeri sendi lutut',
            'Gatal-gatal di kulit',
        ];

        return $keluhans[array_rand($keluhans)];
    }

    private function generateNomorAntrian($dokter, string $tanggalKunjungan, array &$antrianCounters): string
    {
        // Get doctor's name and remove title/degree
        $namaLengkap = $dokter->nama_lengkap;
        $namaLengkap = preg_replace('/^dr\.\s*/i', '', $namaLengkap); // Remove "dr."
        $namaLengkap = preg_replace('/,\s*Sp\.[A-Z]+/i', '', $namaLengkap); // Remove specialty like ", Sp.PD"
        $namaLengkap = trim($namaLengkap);

        // Split name into words
        $words = explode(' ', $namaLengkap);

        // Generate initials
        if (count($words) === 1) {
            // If only 1 word, use D + first letter
            $inisial = 'D' . strtoupper(substr($words[0], 0, 1));
        } else {
            // Take first letter of first 2 words only
            $inisial = strtoupper(substr($words[0], 0, 1)) . strtoupper(substr($words[1], 0, 1));
        }

        // Doctor ID (3 digits)
        $dokterId = str_pad($dokter->dokter_id, 3, '0', STR_PAD_LEFT);

        // Format tanggal: YYYYMMDD
        $tanggalFormatted = str_replace('-', '', $tanggalKunjungan);

        // Generate counter key
        $counterKey = $dokter->dokter_id . '-' . $tanggalKunjungan;

        // Increment counter
        if (!isset($antrianCounters[$counterKey])) {
            $antrianCounters[$counterKey] = 1;
        } else {
            $antrianCounters[$counterKey]++;
        }

        // Nomor urut (2 digits)
        $nomorUrut = str_pad($antrianCounters[$counterKey], 2, '0', STR_PAD_LEFT);

        // Format: INISIAL + DOKTERID + TANGGAL + NOMORURUT
        return $inisial . $dokterId . $tanggalFormatted . $nomorUrut;
    }
}
