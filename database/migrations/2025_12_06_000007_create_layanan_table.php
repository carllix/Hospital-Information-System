<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id('layanan_id');
            $table->string('kode_layanan', 20)->unique();
            $table->string('nama_layanan', 255);
            $table->enum('kategori', ['konsultasi', 'tindakan']);
            $table->decimal('harga', 12, 2);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index('kode_layanan');
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
