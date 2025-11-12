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
        // Creates the reviews table and uses $table (a Blueprint) to define its columns
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('place_id')
                  ->constrained('places')
                  ->cascadeOnDelete();
            $table->string('place_name')->nullable();
            $table->string('source')->default('gmaps_scrape_local');
            $table->string('author')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('text')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->datetime('published_at_date')->nullable(); // consolidated + altered to date
            $table->text('owner_response')->nullable();
            $table->datetime('owner_response_publish_date')->nullable();
            $table->text('review_photos')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            
            // Speeds up common queries
            $table->index(['place_id', 'published_at']);
            $table->index(['place_id', 'author']);
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
