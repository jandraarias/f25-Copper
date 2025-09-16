<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('traveler')->after('password');
            // optional but handy for queries/filters:
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // drop the index only if you added it above
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }
};
