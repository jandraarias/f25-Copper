<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('place_preference_option', function (Blueprint $table) {

            $table->id();

            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->foreignId('preference_option_id')->constrained('preference_options')->onDelete('cascade');
            $table->timestamps();
            // Prevent duplicate relationships
            $table->unique(['place_id', 'preference_option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('place_preference_option');
    }
};

