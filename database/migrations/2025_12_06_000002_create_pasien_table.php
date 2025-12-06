<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id('pasien_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('no_rekam_medis', 20)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('nik', 16)->unique();
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->text('alamat');
            $table->string('provinsi', 100);
            $table->string('kota_kabupaten', 100);
            $table->string('kecamatan', 100);
            $table->string('no_telepon', 15);
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('wearable_device_id', 50)->unique()->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index('no_rekam_medis');
            $table->index('nik');
            $table->index('nama_lengkap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
