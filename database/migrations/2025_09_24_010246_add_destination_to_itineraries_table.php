<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add the column if it doesn't already exist
        if (! Schema::hasColumn('itineraries', 'destination')) {
            Schema::table('itineraries', function (Blueprint $table) {
                $table->string('destination')->nullable()
                    // "after" is ignored by SQLite, fine on MySQL:
                    ->after('description');
            });
        }
    }

    public function down(): void
    {
        // Drop the column if it exists
        if (Schema::hasColumn('itineraries', 'destination')) {
            Schema::table('itineraries', function (Blueprint $table) {
                $table->dropColumn('destination');
            });
        }
    }
};
