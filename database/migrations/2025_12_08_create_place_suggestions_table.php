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
        Schema::create('place_suggestions', function (Blueprint $table) {
            $table->id();

            // Links to the expert suggestion
            $table->foreignId('expert_suggestion_id')
                ->nullable()
                ->constrained('expert_suggestions')
                ->nullOnDelete();

            // Expert who submitted the suggestion
            $table->foreignId('expert_id')
                ->constrained('experts')
                ->cascadeOnDelete();

            // Place details
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable(); // activity, food, etc.
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lon', 11, 8)->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->integer('num_reviews')->nullable()->default(0);
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('google_maps_url')->nullable();

            // Status: pending, approved, rejected, converted_to_place
            $table->enum('status', ['pending', 'approved', 'rejected', 'converted_to_place'])->default('pending');

            // If approved and converted to a Place model
            $table->foreignId('place_id')
                ->nullable()
                ->constrained('places')
                ->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index(['expert_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_suggestions');
    }
};
