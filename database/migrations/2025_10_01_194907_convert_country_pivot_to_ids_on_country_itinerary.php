<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('country_itinerary', function (Blueprint $table) {
            // 1) Add the new FK column as nullable first so we can backfill safely
            $table->foreignId('country_id')
                ->nullable()
                ->after('itinerary_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            // We’ll drop old constraints after we migrate data
        });

        // 2) Backfill country_id from the existing "country" string column.
        //    We handle both ISO-2 codes (e.g., "FR") and full names (e.g., "France").
        DB::table('country_itinerary')
            ->orderBy('id')
            ->chunk(1000, function ($rows) {
                foreach ($rows as $row) {
                    $val = is_null($row->country) ? null : trim($row->country);
                    if ($val === null || $val === '') {
                        continue;
                    }

                    // Try by code first (two letters)
                    $country = null;
                    if (mb_strlen($val) === 2) {
                        $country = DB::table('countries')
                            ->where('code', strtoupper($val))
                            ->first(['id']);
                    }

                    // Fallback: try by name (exact match)
                    if (!$country) {
                        $country = DB::table('countries')
                            ->where('name', $val)
                            ->first(['id']);
                    }

                    if ($country) {
                        DB::table('country_itinerary')
                            ->where('id', $row->id)
                            ->update(['country_id' => $country->id]);
                    }
                }
            });

        // 3) Make the new column required, adjust indexes/uniques, and drop the old column + indexes
        Schema::table('country_itinerary', function (Blueprint $table) {
            // Enforce NOT NULL now that we’ve tried to backfill
            $table->unsignedBigInteger('country_id')->nullable(false)->change();

            // Drop the old unique and index on the "country" column
            // (Laravel will infer the index names)
            $table->dropUnique(['itinerary_id', 'country']);
            $table->dropIndex(['country']);

            // Add the new unique pair
            $table->unique(['itinerary_id', 'country_id']);

            // Finally, drop the old column
            $table->dropColumn('country');
        });
    }

    public function down(): void
    {
        // Reverse: reintroduce "country" string column and try to backfill from countries.code
        Schema::table('country_itinerary', function (Blueprint $table) {
            $table->string('country', 64)->nullable()->after('itinerary_id');

            // Remove the new unique & add back old ones
            $table->dropUnique(['itinerary_id', 'country_id']);
            $table->unique(['itinerary_id', 'country']);
            $table->index('country');
        });

        // Backfill "country" from countries.code
        DB::table('country_itinerary')
            ->orderBy('id')
            ->chunk(1000, function ($rows) {
                foreach ($rows as $row) {
                    if ($row->country_id) {
                        $code = DB::table('countries')
                            ->where('id', $row->country_id)
                            ->value('code');
                        if ($code) {
                            DB::table('country_itinerary')
                                ->where('id', $row->id)
                                ->update(['country' => $code]);
                        }
                    }
                }
            });

        Schema::table('country_itinerary', function (Blueprint $table) {
            // Make "country" required again
            $table->string('country', 64)->nullable(false)->change();

            // Drop FK and the country_id column
            $table->dropConstrainedForeignId('country_id');
        });
    }
};
