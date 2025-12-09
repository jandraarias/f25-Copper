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
        Schema::create('expert_suggestions', function (Blueprint $table) {
            $table->id();

            // Links to itinerary item being replaced
            $table->foreignId('itinerary_item_id')
                ->constrained('itinerary_items')
                ->cascadeOnDelete();

            // Links to expert making the suggestion
            $table->foreignId('expert_id')
                ->constrained('experts')
                ->cascadeOnDelete();

            // The suggested replacement place
            $table->foreignId('place_id')
                ->nullable()
                ->constrained('places')
                ->nullOnDelete();

            // Type: 'replacement' (replacing existing activity), 'new_place' (suggesting a new place)
            $table->enum('type', ['replacement', 'new_place'])->default('replacement');

            // Status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Reason/notes for the suggestion
            $table->text('reason')->nullable();

            $table->timestamps();

            // Indexes for queries
            $table->index(['itinerary_item_id', 'expert_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_suggestions');
    }
};
