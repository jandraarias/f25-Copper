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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();

            // Relationship to traveler
            $table->foreignId('traveler_id')
                ->constrained()
                ->cascadeOnDelete();

            // Public sharing token
            $table->uuid('public_uuid')->nullable()->unique();

            // Basic itinerary fields
            $table->string('name');
            $table->string('destination')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();

            // Collaborative flag
            $table->boolean('is_collaborative')->default(false);

            // Preferences linkage
            $table->foreignId('preference_profile_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
