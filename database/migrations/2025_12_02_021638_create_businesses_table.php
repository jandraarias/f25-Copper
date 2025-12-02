<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            // Linked user account
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Basic business info
            $table->string('name');
            $table->string('city')->index();

            // Website field (required by seeder)
            $table->string('website')->nullable();

            // Profile & description
            $table->string('profile_photo_path')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
