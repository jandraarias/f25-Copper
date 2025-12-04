<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_itinerary_invitations', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('itinerary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expert_id')->constrained('experts')->cascadeOnDelete();
            $table->foreignId('traveler_id')->constrained('travelers')->cascadeOnDelete();
            
            // Status: pending, accepted, declined
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            
            $table->timestamps();
            
            // Prevent duplicate invitations
            $table->unique(['itinerary_id', 'expert_id']);
            
            // Indexes for queries
            $table->index(['expert_id', 'status']);
            $table->index(['itinerary_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_itinerary_invitations');
    }
};
