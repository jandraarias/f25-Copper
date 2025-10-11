<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('travelers', function (Blueprint $table) {
            if (Schema::hasColumn('travelers', 'name')) {
                $table->dropColumn('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('travelers', function (Blueprint $table) {
            if (! Schema::hasColumn('travelers', 'name')) {
                $table->string('name')->nullable(); // make nullable for rollback safety
            }
        });
    }
};
