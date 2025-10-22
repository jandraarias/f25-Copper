<?php

// database/migrations/2025_10_21_000000_update_itinerary_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('itinerary_items', function (Blueprint $table) {
            if (!Schema::hasColumn('itinerary_items', 'place_id')) {
                $table->foreignId('place_id')->nullable()->constrained('places')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('itinerary_items', function (Blueprint $table) {
            if (Schema::hasColumn('itinerary_items', 'place_id')) {
                $table->dropConstrainedForeignId('place_id');
            }
        });
    }
};
