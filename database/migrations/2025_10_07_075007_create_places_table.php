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
        // Creates the places table and uses $table (a Blueprint) to define its columns
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->decimal('rating', 3, 2)->nullable(); // ← Consolidated here
            $table->string('category')->nullable();
            $table->string('source')->default('gmaps_scrape_local'); //typo fixed defualt to default 
            $table->json('meta')->nullable();
            $table->timestamps();

            // A datbase index to make queries faster
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
