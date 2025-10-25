<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('itinerary_items', function (Blueprint $table) {
            if (!Schema::hasColumn('itinerary_items', 'rating')) {
                $table->decimal('rating', 3, 1)->nullable()->after('location');
            }
            if (!Schema::hasColumn('itinerary_items', 'google_maps_url')) {
                $table->string('google_maps_url')->nullable()->after('rating');
            }
        });
    }

    public function down()
    {
        Schema::table('itinerary_items', function (Blueprint $table) {
            $table->dropColumn(['rating', 'google_maps_url']);
        });
    }
};
