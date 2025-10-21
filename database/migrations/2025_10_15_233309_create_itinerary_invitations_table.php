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
        Schema::create('itinerary_invitations', function (Blueprint $table) {
            $table->id();

            // Link to itinerary (cascade delete removes related invitations)
            $table->foreignId('itinerary_id')
                ->constrained()
                ->cascadeOnDelete();

            // Email being invited
            $table->string('email')->index();

            // Unique token for invitation link
            $table->uuid('token')->unique();

            // Invitation status
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');

            // Standard timestamps
            $table->timestamps();

            // Prevent multiple invites to same email for same itinerary
            $table->unique(['itinerary_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_invitations');
    }
};
