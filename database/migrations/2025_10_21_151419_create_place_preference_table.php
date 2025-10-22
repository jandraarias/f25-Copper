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
        Schema::create('place_preference', function (Blueprint $table) {
            $table->foreignId('place_id')->constrained()->onDelete('cascade');
            $table->foreignId('preferences_id')->constrained()->onDelete('cascade');
            $table->primary(['place_id', 'preferences_id']); // Composite primary key;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_preference');
    }
};
