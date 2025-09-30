<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Remove orphans (itineraries pointing to non-existent travelers)
        DB::table('itineraries')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('travelers')
                  ->whereColumn('travelers.id', 'itineraries.traveler_id');
            })->delete();

        // 2) Add the foreign key with cascade
        Schema::table('itineraries', function (Blueprint $table) {
            // If a stray FK exists from a previous attempt, drop it first (safe no-op on most DBs)
            try {
                $table->dropForeign(['traveler_id']);
            } catch (\Throwable $e) { /* ignore */ }

            $table->foreign('traveler_id')
                ->references('id')->on('travelers')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            try {
                $table->dropForeign(['traveler_id']);
            } catch (\Throwable $e) { /* ignore */ }
        });
    }
};
