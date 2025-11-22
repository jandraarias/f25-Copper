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
        Schema::create('itinerary_items', function (Blueprint $table) {
            $table->id();

            // Parent itinerary
            $table->foreignId('itinerary_id')
                ->constrained()
                ->cascadeOnDelete();

            // Core fields
            $table->string('type');  
            $table->string('title');
            $table->string('location');

            // Ratings & maps
            $table->decimal('rating', 3, 1)->nullable();
            $table->string('google_maps_url')->nullable();

            // Relationship to places
            $table->foreignId('place_id')
                ->nullable()
                ->constrained('places')
                ->nullOnDelete();

            // Optional scheduling
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->text('details')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_items');
    }
};
