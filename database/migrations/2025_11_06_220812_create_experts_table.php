<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id();

            // User relationship
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Profile fields
            $table->string('name');
            $table->string('city')->index();
            $table->string('photo_url')->nullable();
            $table->text('bio')->nullable();

            // Added fields to match your Expert model & form
            $table->string('expertise')->nullable();
            $table->string('languages')->nullable();
            $table->integer('experience_years')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experts');
    }
};
