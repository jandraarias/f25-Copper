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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // External review ID (e.g., Google Review ID)
            $table->unsignedBigInteger('review_id')->nullable();

            // === Relationships ===
            $table->foreignId('place_id')
                  ->nullable()
                  ->constrained('places')
                  ->nullOnDelete();

            // === Core Identifying Fields ===
            $table->string('place_name')->nullable();     // Name of place at time of scrape/save
            $table->string('reviewer_name')->nullable();  // Human-readable reviewer identifier

            // === Review Content ===
            $table->decimal('rating', 3, 1)->nullable();  // e.g. 4.5
            $table->text('review_text')->nullable();
            $table->date('reviewed_at')->nullable();      // Date the review was written
            $table->text('review_keywords')->nullable();  // Optional extracted keywords/tags

            // === Additional Meta Fields (merged from the old create migration) ===
            $table->string('source')->default('gmaps_scrape_local')->nullable();
            $table->text('owner_response')->nullable();
            $table->datetime('owner_response_publish_date')->nullable();
            $table->text('review_photos')->nullable();    // JSON/CSV blob depending on scraper logic
            $table->timestamp('fetched_at')->nullable();
            $table->json('meta')->nullable();
            $table->datetime('published_at_date')->nullable(); // old column preserved

            $table->timestamps();

            // === Indexes ===
            $table->index(['place_id', 'reviewed_at']);
            $table->index(['place_id', 'reviewer_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
