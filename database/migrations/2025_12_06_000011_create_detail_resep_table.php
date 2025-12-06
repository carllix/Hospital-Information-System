<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_resep', function (Blueprint $table) {
            $table->id('detail_resep_id');
            $table->foreignId('resep_id')->constrained('resep', 'resep_id')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obat', 'obat_id')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('dosis', 50);
            $table->text('aturan_pakai');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('resep_id');
            $table->index('obat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_resep');
    }
};
