<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Staf;
use App\Models\Dokter;
use App\Models\Pendaftaran;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use App\Models\Pembayaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        $this->command->info('Clearing existing data...');

        // Create Users and Pasien
        $this->command->info('Creating Pasien...');
        $this->createPasien();

        // Create Staf
        $this->command->info('Creating Staf...');
        $this->createStaf();

        // Create Dokter
        $this->command->info('Creating Dokter...');
        $this->createDokter();

        // Create Pendaftaran
        $this->command->info('Creating Pendaftaran...');
        $this->createPendaftaran();

        // Create Tagihan and Pembayaran
        $this->command->info('Creating Tagihan and Pembayaran...');
        $this->createTagihanPembayaran();

        // Create Obat
        $this->command->info('Creating Obat...');
        $this->createObat();

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

        // Create Wearable Data
        $this->command->info('Creating Wearable Data...');
        $this->createWearableData();

        $this->command->info('Database seeding completed successfully!');
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
                'no_telepon' => '081234567801',
                'alamat' => 'Jl. Merdeka No. 123',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'A+',
            ],
            [
                'email' => 'pasien2@test.com',
                'nama' => 'Siti Nurhaliza',
                'nik' => '3201012345670002',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1985-08-20',
                'no_telepon' => '081234567802',
                'alamat' => 'Jl. Gatot Subroto No. 45',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'B+',
            ],
            [
                'email' => 'pasien3@test.com',
                'nama' => 'Ahmad Wijaya',
                'nik' => '3201012345670003',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1995-03-10',
                'no_telepon' => '081234567803',
                'alamat' => 'Jl. Asia Afrika No. 78',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'O+',
            ],
            [
                'email' => 'pasien4@test.com',
                'nama' => 'Dewi Lestari',
                'nik' => '3201012345670004',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1992-11-25',
                'no_telepon' => '081234567804',
                'alamat' => 'Jl. Dago No. 234',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'golongan_darah' => 'AB+',
            ],
            [
                'email' => 'pasien5@test.com',
                'nama' => 'Rudi Hermawan',
                'nik' => '3201012345670005',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1988-07-18',
                'no_telepon' => '081234567805',
                'alamat' => 'Jl. Braga No. 56',
                'kota' => 'Bandung',
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
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'kota_kabupaten' => $data['kota'],
                'provinsi' => $data['provinsi'],
                'kewarganegaraan' => 'Indonesia',
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
                'nip' => 'NIP001',
                'bagian' => 'pendaftaran',
                'role' => 'pendaftaran',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1987-04-12',
                'no_telepon' => '081234567901',
            ],
            [
                'email' => 'pendaftaran2@test.com',
                'nama' => 'Andi Prasetyo',
                'nik' => '3201012345670102',
                'nip' => 'NIP002',
                'bagian' => 'pendaftaran',
                'role' => 'pendaftaran',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1990-09-22',
                'no_telepon' => '081234567902',
            ],
            [
                'email' => 'kasir1@test.com',
                'nama' => 'Maya Sari',
                'nik' => '3201012345670103',
                'nip' => 'NIP003',
                'bagian' => 'kasir',
                'role' => 'kasir_klinik',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1989-06-15',
                'no_telepon' => '081234567903',
            ],
            [
                'email' => 'apoteker1@test.com',
                'nama' => 'Rizki Firmansyah',
                'nik' => '3201012345670104',
                'nip' => 'NIP004',
                'bagian' => 'farmasi',
                'role' => 'apoteker',
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_lahir' => '1991-12-08',
                'no_telepon' => '081234567904',
            ],
            [
                'email' => 'lab1@test.com',
                'nama' => 'Ratna Dewi',
                'nik' => '3201012345670105',
                'nip' => 'NIP005',
                'bagian' => 'laboratorium',
                'role' => 'lab',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1988-03-30',
                'no_telepon' => '081234567905',
            ],
        ];

        foreach ($stafData as $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => $data['role'],
            ]);

            Staf::create([
                'user_id' => $user->user_id,
                'nip' => $data['nip'],
                'nama_lengkap' => $data['nama'],
                'nik' => $data['nik'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => 'Jl. Sudirman No. 123, Bandung',
                'kota_kabupaten' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kewarganegaraan' => 'Indonesia',
                'no_telepon' => $data['no_telepon'],
                'bagian' => $data['bagian'],
            ]);
        }
    }

    private function createDokter(): void
    {
        $dokterData = [
            [
                'email' => 'dr.andi@test.com',
                'nama' => 'dr. Andi Kusuma, Sp.PD',
                'nik' => '3201012345670201',
                'nip' => 'DOK001',
                'spesialisasi' => 'Penyakit Dalam',
                'no_str' => 'STR-PD-001',
                'jadwal' => [
                    ['hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
                    ['hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
                    ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
                ],
            ],
            [
                'email' => 'dr.sari@test.com',
                'nama' => 'dr. Sari Wijayanti, Sp.A',
                'nik' => '3201012345670202',
                'nip' => 'DOK002',
                'spesialisasi' => 'Anak',
                'no_str' => 'STR-PA-002',
                'jadwal' => [
                    ['hari' => 'Selasa', 'jam_mulai' => '09:00', 'jam_selesai' => '14:00'],
                    ['hari' => 'Kamis', 'jam_mulai' => '09:00', 'jam_selesai' => '14:00'],
                    ['hari' => 'Sabtu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
                ],
            ],
            [
                'email' => 'dr.budi@test.com',
                'nama' => 'dr. Budi Santoso, Sp.OG',
                'nik' => '3201012345670203',
                'nip' => 'DOK003',
                'spesialisasi' => 'Kandungan',
                'no_str' => 'STR-OG-003',
                'jadwal' => [
                    ['hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
                    ['hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
                    ['hari' => 'Jumat', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
                ],
            ],
            [
                'email' => 'dr.maya@test.com',
                'nama' => 'dr. Maya Lestari, Sp.JP',
                'nik' => '3201012345670204',
                'nip' => 'DOK004',
                'spesialisasi' => 'Jantung',
                'no_str' => 'STR-JP-004',
                'jadwal' => [
                    ['hari' => 'Selasa', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00'],
                    ['hari' => 'Kamis', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00'],
                ],
            ],
            [
                'email' => 'dr.rudi@test.com',
                'nama' => 'dr. Rudi Hermawan, Sp.B',
                'nik' => '3201012345670205',
                'nip' => 'DOK005',
                'spesialisasi' => 'Bedah',
                'no_str' => 'STR-SB-005',
                'jadwal' => [
                    ['hari' => 'Senin', 'jam_mulai' => '10:00', 'jam_selesai' => '15:00'],
                    ['hari' => 'Rabu', 'jam_mulai' => '10:00', 'jam_selesai' => '15:00'],
                    ['hari' => 'Jumat', 'jam_mulai' => '10:00', 'jam_selesai' => '15:00'],
                ],
            ],
        ];

        foreach ($dokterData as $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'dokter',
            ]);

            Dokter::create([
                'user_id' => $user->user_id,
                'nip' => $data['nip'],
                'nama_lengkap' => $data['nama'],
                'nik' => $data['nik'],
                'tanggal_lahir' => '1980-01-01',
                'jenis_kelamin' => str_contains($data['nama'], 'dr. Sari') || str_contains($data['nama'], 'dr. Maya') ? 'Perempuan' : 'Laki-Laki',
                'alamat' => 'Jl. Dokter No. 456, Bandung',
                'kota_kabupaten' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kewarganegaraan' => 'Indonesia',
                'no_telepon' => '081298765' . substr($data['nip'], -3),
                'spesialisasi' => $data['spesialisasi'],
                'no_str' => $data['no_str'],
                'jadwal_praktik' => $data['jadwal'],
            ]);
        }
    }

    private function createPendaftaran(): void
    {
        $pasiens = Pasien::all();
        $dokters = Dokter::all();
        $stafPendaftaran = Staf::where('bagian', 'pendaftaran')->first();

        // Create pendaftaran for today
        foreach ($pasiens->take(3) as $index => $pasien) {
            $dokter = $dokters->random();
            $initial = strtoupper(substr($dokter->nama_lengkap, 4, 1)); // Skip "dr. "

            Pendaftaran::create([
                'pasien_id' => $pasien->pasien_id,
                'dokter_id' => $dokter->dokter_id,
                'tanggal_daftar' => now()->subHours(3 - $index),
                'nomor_antrian' => $initial . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'keluhan_utama' => $this->getRandomKeluhan(),
                'staf_pendaftaran_id' => $stafPendaftaran?->staf_id,
                'status' => $index === 0 ? 'dipanggil' : 'menunggu',
            ]);
        }

        // Create past pendaftaran
        foreach ($pasiens as $pasien) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $dokter = $dokters->random();
                $initial = strtoupper(substr($dokter->nama_lengkap, 4, 1));

                Pendaftaran::create([
                    'pasien_id' => $pasien->pasien_id,
                    'dokter_id' => $dokter->dokter_id,
                    'tanggal_daftar' => now()->subDays(rand(1, 30)),
                    'nomor_antrian' => $initial . str_pad(rand(1, 50), 3, '0', STR_PAD_LEFT),
                    'keluhan_utama' => $this->getRandomKeluhan(),
                    'staf_pendaftaran_id' => $stafPendaftaran?->staf_id,
                    'status' => 'selesai',
                ]);
            }
        }
    }

    private function createTagihanPembayaran(): void
    {
        $pasiens = Pasien::all();
        $pendaftarans = Pendaftaran::where('status', 'selesai')->get();
        $kasir = Staf::where('bagian', 'kasir')->first();
        $kwitansiCounter = 1;

        foreach ($pasiens as $pasien) {
            // Create tagihan belum bayar
            $tagihan1 = Tagihan::create([
                'pendaftaran_id' => $pendaftarans->random()->pendaftaran_id,
                'pasien_id' => $pasien->pasien_id,
                'jenis_tagihan' => 'konsultasi',
                'subtotal' => 150000,
                'total_tagihan' => 150000,
                'status' => 'belum_bayar',
                'created_at' => now()->subDays(rand(1, 5)),
            ]);

            DetailTagihan::create([
                'tagihan_id' => $tagihan1->tagihan_id,
                'jenis_item' => 'konsultasi',
                'nama_item' => 'Konsultasi Dokter Spesialis',
                'jumlah' => 1,
                'harga_satuan' => 150000,
                'subtotal' => 150000,
            ]);

            // Create tagihan lunas
            $tagihan2 = Tagihan::create([
                'pendaftaran_id' => $pendaftarans->random()->pendaftaran_id,
                'pasien_id' => $pasien->pasien_id,
                'jenis_tagihan' => 'obat',
                'subtotal' => 250000,
                'total_tagihan' => 250000,
                'status' => 'lunas',
                'created_at' => now()->subDays(rand(6, 15)),
            ]);

            DetailTagihan::create([
                'tagihan_id' => $tagihan2->tagihan_id,
                'jenis_item' => 'obat',
                'nama_item' => 'Paracetamol 500mg',
                'jumlah' => 20,
                'harga_satuan' => 5000,
                'subtotal' => 100000,
            ]);

            DetailTagihan::create([
                'tagihan_id' => $tagihan2->tagihan_id,
                'jenis_item' => 'obat',
                'nama_item' => 'Amoxicillin 500mg',
                'jumlah' => 15,
                'harga_satuan' => 10000,
                'subtotal' => 150000,
            ]);

            // Create pembayaran for lunas tagihan
            Pembayaran::create([
                'tagihan_id' => $tagihan2->tagihan_id,
                'tanggal_bayar' => now()->subDays(rand(6, 15)),
                'metode_pembayaran' => collect(['tunai', 'debit', 'kredit', 'transfer'])->random(),
                'jumlah_bayar' => 250000,
                'kasir_id' => $kasir?->staf_id ?? 1,
                'no_kwitansi' => 'KWT-' . now()->format('Ymd') . '-' . str_pad($kwitansiCounter++, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(rand(6, 15)),
            ]);

            // Create tagihan lab lunas
            $tagihan3 = Tagihan::create([
                'pendaftaran_id' => $pendaftarans->random()->pendaftaran_id,
                'pasien_id' => $pasien->pasien_id,
                'jenis_tagihan' => 'lab',
                'subtotal' => 350000,
                'total_tagihan' => 350000,
                'status' => 'lunas',
                'created_at' => now()->subDays(rand(16, 30)),
            ]);

            DetailTagihan::create([
                'tagihan_id' => $tagihan3->tagihan_id,
                'jenis_item' => 'lab',
                'nama_item' => 'Cek Darah Lengkap',
                'jumlah' => 1,
                'harga_satuan' => 200000,
                'subtotal' => 200000,
            ]);

            DetailTagihan::create([
                'tagihan_id' => $tagihan3->tagihan_id,
                'jenis_item' => 'lab',
                'nama_item' => 'Rontgen Thorax',
                'jumlah' => 1,
                'harga_satuan' => 150000,
                'subtotal' => 150000,
            ]);

            Pembayaran::create([
                'tagihan_id' => $tagihan3->tagihan_id,
                'tanggal_bayar' => now()->subDays(rand(16, 30)),
                'metode_pembayaran' => collect(['tunai', 'debit', 'transfer'])->random(),
                'jumlah_bayar' => 350000,
                'kasir_id' => $kasir?->staf_id ?? 1,
                'no_kwitansi' => 'KWT-' . now()->format('Ymd') . '-' . str_pad($kwitansiCounter++, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(rand(16, 30)),
            ]);
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

    private function createObat(): void
    {
        $obatData = [
            ['kode' => 'OBT-001', 'nama' => 'Paracetamol 500mg', 'kategori' => 'tablet', 'stok' => 1000, 'harga' => 5000, 'satuan' => 'tablet', 'deskripsi' => 'Obat pereda demam dan nyeri'],
            ['kode' => 'OBT-002', 'nama' => 'Amoxicillin 500mg', 'kategori' => 'kapsul', 'stok' => 500, 'harga' => 10000, 'satuan' => 'kapsul', 'deskripsi' => 'Antibiotik untuk infeksi bakteri'],
            ['kode' => 'OBT-003', 'nama' => 'OBH Combi', 'kategori' => 'sirup', 'stok' => 300, 'harga' => 15000, 'satuan' => 'botol', 'deskripsi' => 'Obat batuk berdahak'],
            ['kode' => 'OBT-004', 'nama' => 'Antasida DOEN', 'kategori' => 'tablet', 'stok' => 400, 'harga' => 8000, 'satuan' => 'tablet', 'deskripsi' => 'Obat maag dan gangguan pencernaan'],
            ['kode' => 'OBT-005', 'nama' => 'Ibuprofen 400mg', 'kategori' => 'tablet', 'stok' => 600, 'harga' => 7000, 'satuan' => 'tablet', 'deskripsi' => 'Obat anti inflamasi dan pereda nyeri'],
            ['kode' => 'OBT-006', 'nama' => 'Cetirizine 10mg', 'kategori' => 'tablet', 'stok' => 350, 'harga' => 6000, 'satuan' => 'tablet', 'deskripsi' => 'Obat anti alergi'],
            ['kode' => 'OBT-007', 'nama' => 'Vitamin C 1000mg', 'kategori' => 'tablet', 'stok' => 800, 'harga' => 12000, 'satuan' => 'tablet', 'deskripsi' => 'Suplemen vitamin C'],
            ['kode' => 'OBT-008', 'nama' => 'Omeprazole 20mg', 'kategori' => 'kapsul', 'stok' => 250, 'harga' => 15000, 'satuan' => 'kapsul', 'deskripsi' => 'Obat untuk mengurangi asam lambung'],
            ['kode' => 'OBT-009', 'nama' => 'Metformin 500mg', 'kategori' => 'tablet', 'stok' => 400, 'harga' => 9000, 'satuan' => 'tablet', 'deskripsi' => 'Obat diabetes tipe 2'],
            ['kode' => 'OBT-010', 'nama' => 'Bisolvon', 'kategori' => 'sirup', 'stok' => 200, 'harga' => 18000, 'satuan' => 'botol', 'deskripsi' => 'Obat pengencer dahak'],
        ];

        foreach ($obatData as $obat) {
            DB::table('obat')->insert([
                'kode_obat' => $obat['kode'],
                'nama_obat' => $obat['nama'],
                'kategori' => $obat['kategori'],
                'satuan' => $obat['satuan'],
                'stok' => $obat['stok'],
                'harga' => $obat['harga'],
                'deskripsi' => $obat['deskripsi'],
            ]);
        }
    }

    private function createPemeriksaan(): void
    {
        $pendaftarans = DB::table('pendaftaran')
            ->where('status', 'selesai')
            ->get();

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

        // Get random pendaftaran records, but not more than available
        $count = min(15, $pendaftarans->count());
        foreach ($pendaftarans->random($count) as $pendaftaran) {
            DB::table('pemeriksaan')->insert([
                'pendaftaran_id' => $pendaftaran->pendaftaran_id,
                'pasien_id' => $pendaftaran->pasien_id,
                'dokter_id' => $pendaftaran->dokter_id,
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
                'status_pasien' => collect(['selesai_penanganan', 'dirujuk', 'perlu_resep', 'perlu_lab'])->random(),
            ]);
        }
    }

    private function createResep(): void
    {
        $pemeriksaans = DB::table('pemeriksaan')->get();
        $obats = DB::table('obat')->get();
        $apoteker = DB::table('staf')->where('bagian', 'farmasi')->first();

        $count = min(10, $pemeriksaans->count());
        foreach ($pemeriksaans->random($count) as $pemeriksaan) {
            $resepId = DB::table('resep')->insertGetId([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'pasien_id' => $pemeriksaan->pasien_id,
                'dokter_id' => $pemeriksaan->dokter_id,
                'tanggal_resep' => $pemeriksaan->tanggal_pemeriksaan,
                'apoteker_id' => $apoteker?->staf_id,
                'status' => collect(['diproses', 'selesai'])->random(),
            ], 'resep_id');

            // Add 2-3 obat per resep
            $selectedObats = $obats->random(rand(2, 3));
            foreach ($selectedObats as $obat) {
                $jumlah = rand(10, 30);
                DB::table('detail_resep')->insert([
                    'resep_id' => $resepId,
                    'obat_id' => $obat->obat_id,
                    'jumlah' => $jumlah,
                    'dosis' => rand(1, 3) . 'x sehari',
                    'aturan_pakai' => 'Diminum setelah makan',
                ]);
            }
        }
    }

    private function createPermintaanLab(): void
    {
        $pemeriksaans = DB::table('pemeriksaan')->get();
        $labStaf = DB::table('staf')->where('bagian', 'laboratorium')->first();

        $jenisLab = [
            'darah_lengkap',
            'urine',
            'gula_darah',
            'kolesterol',
            'radiologi',
            'lainnya',
        ];

        $count = min(8, $pemeriksaans->count());
        foreach ($pemeriksaans->random($count) as $pemeriksaan) {
            $permintaanId = DB::table('permintaan_lab')->insertGetId([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'pasien_id' => $pemeriksaan->pasien_id,
                'dokter_id' => $pemeriksaan->dokter_id,
                'tanggal_permintaan' => $pemeriksaan->tanggal_pemeriksaan,
                'jenis_pemeriksaan' => $jenisLab[array_rand($jenisLab)],
                'catatan_permintaan' => 'Pemeriksaan rutin',
                'status' => collect(['menunggu', 'diproses', 'selesai'])->random(),
                'petugas_lab_id' => $labStaf?->staf_id,
            ], 'permintaan_lab_id');

            // Create hasil lab untuk yang sudah selesai
            $permintaan = DB::table('permintaan_lab')->where('permintaan_lab_id', $permintaanId)->first();
            if ($permintaan->status === 'selesai') {
                // Map jenis_pemeriksaan to jenis_test and parameter
                $labResults = [
                    'darah_lengkap' => ['jenis' => 'Darah Lengkap', 'parameter' => 'Hemoglobin', 'hasil' => '14.5', 'satuan' => 'g/dL', 'normal' => '13-17 g/dL'],
                    'urine' => ['jenis' => 'Urine', 'parameter' => 'Protein', 'hasil' => 'Negatif', 'satuan' => null, 'normal' => 'Negatif'],
                    'gula_darah' => ['jenis' => 'Gula Darah', 'parameter' => 'Glukosa Puasa', 'hasil' => '95', 'satuan' => 'mg/dL', 'normal' => '70-100 mg/dL'],
                    'kolesterol' => ['jenis' => 'Kolesterol', 'parameter' => 'Kolesterol Total', 'hasil' => '180', 'satuan' => 'mg/dL', 'normal' => '<200 mg/dL'],
                    'radiologi' => ['jenis' => 'Radiologi', 'parameter' => 'Rontgen Thorax', 'hasil' => 'Normal', 'satuan' => null, 'normal' => 'Tidak ada kelainan'],
                    'lainnya' => ['jenis' => 'Pemeriksaan Lainnya', 'parameter' => 'General Check', 'hasil' => 'Normal', 'satuan' => null, 'normal' => 'Dalam batas normal'],
                ];

                $labData = $labResults[$permintaan->jenis_pemeriksaan] ?? $labResults['lainnya'];

                DB::table('hasil_lab')->insert([
                    'permintaan_lab_id' => $permintaanId,
                    'jenis_test' => $labData['jenis'],
                    'parameter' => $labData['parameter'],
                    'hasil' => $labData['hasil'],
                    'satuan' => $labData['satuan'],
                    'nilai_normal' => $labData['normal'],
                    'keterangan' => 'Tidak ada kelainan yang ditemukan',
                    'tanggal_hasil' => now()->subDays(rand(1, 5)),
                    'petugas_lab_id' => $labStaf?->staf_id,
                ]);
            }
        }
    }

    private function createRujukan(): void
    {
        $pemeriksaans = DB::table('pemeriksaan')
            ->where('status_pasien', 'dirujuk')
            ->get();

        $rsRujukan = [
            'RS Hasan Sadikin Bandung',
            'RS Advent Bandung',
            'RS Santo Borromeus',
            'RS Immanuel Bandung',
        ];

        foreach ($pemeriksaans as $pemeriksaan) {
            DB::table('rujukan')->insert([
                'pemeriksaan_id' => $pemeriksaan->pemeriksaan_id,
                'pasien_id' => $pemeriksaan->pasien_id,
                'dokter_perujuk_id' => $pemeriksaan->dokter_id,
                'tanggal_rujukan' => $pemeriksaan->tanggal_pemeriksaan,
                'rs_tujuan' => $rsRujukan[array_rand($rsRujukan)],
                'alasan_rujukan' => 'Memerlukan pemeriksaan lanjutan dan penanganan spesialis',
                'diagnosa_sementara' => $pemeriksaan->diagnosa,
            ]);
        }
    }

    private function createWearableData(): void
    {
        $pasiens = DB::table('pasien')
            ->whereNotNull('wearable_device_id')
            ->get();

        // Add wearable device for first 2 patients
        DB::table('pasien')->where('pasien_id', 1)->update(['wearable_device_id' => 'DEVICE123']);
        DB::table('pasien')->where('pasien_id', 2)->update(['wearable_device_id' => 'DEVICE456']);

        $pasiens = DB::table('pasien')
            ->whereNotNull('wearable_device_id')
            ->get();

        foreach ($pasiens as $pasien) {
            // Create data for last 7 days
            for ($i = 0; $i < 7; $i++) {
                DB::table('wearable_data')->insert([
                    'pasien_id' => $pasien->pasien_id,
                    'device_id' => $pasien->wearable_device_id,
                    'timestamp' => now()->subDays($i)->setHour(rand(8, 20)),
                    'heart_rate' => rand(60, 100),
                    'oxygen_saturation' => rand(95, 100),
                ]);
            }
        }
    }
}
