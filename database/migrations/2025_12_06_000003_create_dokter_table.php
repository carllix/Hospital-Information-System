<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->id('dokter_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nip_rs', 30)->unique();
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
            $table->string('spesialisasi', 100);
            $table->string('no_str', 17);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index('nip_rs');
            $table->index('nama_lengkap');
            $table->index('spesialisasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
