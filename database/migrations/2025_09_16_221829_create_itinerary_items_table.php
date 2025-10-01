<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itinerary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained()->cascadeOnDelete();

            $table->string('type'); // flight, hotel, activity, etc.
            $table->string('title');
            $table->string('location'); // required here — specific place (hotel name, landmark, etc.)

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->text('details')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_items');
    }
};
