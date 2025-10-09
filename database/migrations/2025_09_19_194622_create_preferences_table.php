<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            // FK to preference_profiles
            $table->foreignId('preference_profile_id')->constrained()->onDelete('cascade');

            // FK to parent preference 
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('preferences')->onDelete('cascade');

            $table->string('key');    // e.g. "food", "lodging", "transportation"
            $table->string('value');  // e.g. "vegetarian", "budget", "train"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
