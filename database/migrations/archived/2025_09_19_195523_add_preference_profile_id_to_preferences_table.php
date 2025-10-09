<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preferences', function (Blueprint $table) {
            // Add the foreign key column if it doesn't exist
            if (!Schema::hasColumn('preferences', 'preference_profile_id')) {
                $table->unsignedBigInteger('preference_profile_id')->after('id');

                $table->foreign('preference_profile_id')
                    ->references('id')
                    ->on('preference_profiles')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('preferences', function (Blueprint $table) {
            if (Schema::hasColumn('preferences', 'preference_profile_id')) {
                $table->dropForeign(['preference_profile_id']);
                $table->dropColumn('preference_profile_id');
            }
        });
    }
};
