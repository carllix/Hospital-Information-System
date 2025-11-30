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
        Schema::create('wearable_data', function (Blueprint $table) {
            $table->id('wearable_data_id');
            $table->foreignId('pasien_id')->constrained('pasien', 'pasien_id')->onDelete('cascade');
            $table->string('device_id', 50)->notNullable();
            $table->dateTime('timestamp')->notNullable();
            $table->integer('heart_rate')->nullable()->comment('BPM');
            $table->integer('oxygen_saturation')->nullable()->comment('SpO2 %');
            $table->timestamp('created_at')->useCurrent();

            $table->index('pasien_id');
            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wearable_data');
    }
};
