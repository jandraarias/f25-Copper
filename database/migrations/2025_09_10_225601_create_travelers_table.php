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
        Schema::create('travelers', function (Blueprint $table) {
            $table->id();

            // Travelers always belong to a user
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // PII has been moved to users table, so only keep profile fields
            $table->text('bio')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travelers');
    }
};
