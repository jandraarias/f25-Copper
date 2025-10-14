<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('reviews', function (Blueprint $t) {
            $t->date('published_at_date')->nullable()->change();
        });
    }
    public function down(): void {
        Schema::table('reviews', function (Blueprint $t) {
            $t->timestamp('published_at_date')->nullable()->change();
        });
    }
};
