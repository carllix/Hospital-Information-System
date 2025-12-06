<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_lab', function (Blueprint $table) {
            $table->id('hasil_lab_id');
            $table->foreignId('permintaan_lab_id')->constrained('permintaan_lab', 'permintaan_lab_id')->onDelete('cascade');
            $table->string('jenis_test', 100);
            $table->string('parameter', 100);
            $table->string('hasil', 100);
            $table->string('satuan', 20)->nullable();
            $table->string('nilai_normal', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_hasil_url', 255)->nullable();
            $table->dateTime('tanggal_hasil')->useCurrent();
            $table->foreignId('petugas_lab_id')->constrained('staf', 'staf_id')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->index('permintaan_lab_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_lab');
    }
};
