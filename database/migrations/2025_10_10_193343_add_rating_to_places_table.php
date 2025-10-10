<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('places', function (Blueprint $t) {
            $t->decimal('rating', 3, 2)->nullable()->after('lon'); 
            // e.g. 4.35 stored safely
        });
    }

    public function down(): void {
        Schema::table('places', function (Blueprint $t) {
            $t->dropColumn('rating');
        });
    }
};
