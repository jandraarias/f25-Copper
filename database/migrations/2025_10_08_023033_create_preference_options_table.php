<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preference_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['main', 'sub', 'sub-sub']);
            $table->string('category')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('preference_options')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preference_options');
    }
};

