<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('travelers', function (Blueprint $table) {
            // Only add if the column doesn't already exist
            if (! Schema::hasColumn('travelers', 'user_id')) {
                $table->foreignId('user_id')
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('travelers', function (Blueprint $table) {
            // Only drop if the column exists
            if (Schema::hasColumn('travelers', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
