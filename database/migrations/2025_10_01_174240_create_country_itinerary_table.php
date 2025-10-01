<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_itinerary', function (Blueprint $table) {
            $table->id();

            // Parent itinerary
            $table->foreignId('itinerary_id')
                ->constrained()
                ->cascadeOnDelete();

            // Store a country per row. Keep it flexible so you can pass either "FR" or "France".
            $table->string('country', 64);

            // Prevent duplicates like (itinerary_id=1, country="FR") twice
            $table->unique(['itinerary_id', 'country']);

            // Helpful for queries like "all itineraries that include France"
            $table->index('country');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_itinerary');
    }
};
