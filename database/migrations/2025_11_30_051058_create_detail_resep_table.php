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
        Schema::create('detail_resep', function (Blueprint $table) {
            $table->id('detail_resep_id');
            $table->foreignId('resep_id')->constrained('resep', 'resep_id')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obat', 'obat_id')->onDelete('cascade');
            $table->integer('jumlah')->notNullable();
            $table->string('dosis', 50)->notNullable();
            $table->text('aturan_pakai')->notNullable();
            $table->text('keterangan')->nullable();

            $table->index('resep_id');
            $table->index('obat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_resep');
    }
};
