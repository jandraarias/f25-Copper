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
        Schema::create('places', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('photo_url')->nullable(); // merged from modifier

            $table->text('description')->nullable();
            $table->unsignedInteger('num_reviews')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->string('categories')->nullable();
            $table->string('tags')->nullable();
            $table->string('image')->nullable();
            $table->string('source')->default('gmaps_scrape_local');
            $table->json('meta')->nullable();

            $table->timestamps();

            // indexing for faster proximity/name search
            $table->index(['name', 'lat', 'lon']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
