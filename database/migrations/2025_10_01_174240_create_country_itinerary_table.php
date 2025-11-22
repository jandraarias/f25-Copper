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
        Schema::create('country_itinerary', function (Blueprint $table) {
            $table->id();

            // Relationship to itineraries
            $table->foreignId('itinerary_id')
                ->constrained()
                ->cascadeOnDelete();

            // New normalized country reference (NOT NULL)
            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            // Prevent duplicates
            $table->unique(['itinerary_id', 'country_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_itinerary');
    }
};
