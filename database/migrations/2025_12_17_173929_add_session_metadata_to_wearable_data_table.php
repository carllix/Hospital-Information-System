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
        Schema::table('wearable_data', function (Blueprint $table) {
            // Add column untuk session metadata
            $table->json('session_metadata')->nullable()->after('oxygen_saturation');
            
            // Add index untuk query session data
            $table->index(['device_id', 'timestamp']);
            $table->index(['pasien_id', 'device_id', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wearable_data', function (Blueprint $table) {
            $table->dropIndex(['device_id', 'timestamp']);
            $table->dropIndex(['pasien_id', 'device_id', 'timestamp']);
            $table->dropColumn('session_metadata');
        });
    }
};

// Run: php artisan make:migration add_session_metadata_to_wearable_data_table