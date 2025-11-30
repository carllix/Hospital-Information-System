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
        Schema::create('obat', function (Blueprint $table) {
            $table->id('obat_id');
            $table->string('kode_obat', 20)->unique()->notNullable();
            $table->string('nama_obat', 255)->notNullable();
            $table->enum('kategori', ['tablet', 'kapsul', 'sirup', 'salep', 'injeksi'])->notNullable();
            $table->string('satuan', 20)->notNullable();
            $table->integer('stok')->notNullable()->default(0);
            $table->decimal('harga', 12, 2)->notNullable();
            $table->text('deskripsi')->nullable();

            $table->index('kode_obat');
            $table->index('nama_obat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
