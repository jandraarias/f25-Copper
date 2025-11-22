<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preference_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traveler_id')->constrained()->onDelete('cascade');

            $table->string('name'); // e.g. "Summer Europe Trip"
            $table->enum('budget', ['low', 'medium', 'high'])->nullable();
            $table->json('interests')->nullable(); // e.g. ["food", "hiking", "museums"]
            $table->string('preferred_climate')->nullable(); // e.g. "warm", "cold", "temperate"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preference_profiles');
    }
};
