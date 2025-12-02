<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->id('dokter_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nip', 30)->unique()->notNullable();
            $table->string('nama_lengkap', 100)->notNullable();
            $table->string('nik', 16)->unique()->notNullable();
            $table->date('tanggal_lahir')->notNullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->notNullable();
            $table->text('alamat')->notNullable();
            $table->string('kota_kabupaten', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kewarganegaraan', 50)->nullable();
            $table->string('no_telepon', 15)->notNullable();
            $table->string('spesialisasi', 100)->nullable();
            $table->string('no_str', 50)->nullable();
            $table->json('jadwal_praktik')->nullable();

            $table->index('nip');
            $table->index('nama_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
