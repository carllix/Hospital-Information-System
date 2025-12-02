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
        Schema::create('staf', function (Blueprint $table) {
            $table->id('staf_id');
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
            $table->enum('bagian', ['pendaftaran', 'farmasi', 'laboratorium', 'kasir'])->notNullable();

            $table->index('nip');
            $table->index('bagian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staf');
    }
};
