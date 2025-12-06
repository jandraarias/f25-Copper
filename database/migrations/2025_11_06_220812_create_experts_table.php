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

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('city')->index();

            $table->string('profile_photo_path')->nullable();   // <-- RENAMED
            $table->text('bio')->nullable();

            $table->string('expertise')->nullable();
            $table->string('languages')->nullable();
            $table->integer('experience_years')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->string('availability')->nullable();
            

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experts');
    }
};
