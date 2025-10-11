<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add new column (nullable) ONLY if it doesn't exist
        if (!Schema::hasColumn('country_itinerary', 'country_id')) {
            Schema::table('country_itinerary', function (Blueprint $table) {
                $table->unsignedBigInteger('country_id')->nullable()->after('itinerary_id');
            });
        }

        // 2) Backfill country_id from "country" string ONLY if 'country' still exists
        if (Schema::hasColumn('country_itinerary', 'country')) {
            DB::table('country_itinerary')
                ->select(['itinerary_id', 'country'])
                ->orderBy('itinerary_id')
                ->chunk(1000, function ($rows) {
                    foreach ($rows as $row) {
                        $val = is_null($row->country) ? null : trim($row->country);
                        if ($val === null || $val === '') continue;

                        $country = null;
                        if (mb_strlen($val) === 2) {
                            $country = DB::table('countries')
                                ->where('code', strtoupper($val))
                                ->first(['id']);
                        }
                        if (!$country) {
                            $country = DB::table('countries')
                                ->where('name', $val)
                                ->first(['id']);
                        }

                        if ($country) {
                            DB::table('country_itinerary')
                                ->where('itinerary_id', $row->itinerary_id)
                                ->where('country', $row->country)
                                ->update(['country_id' => $country->id]);
                        }
                    }
                });
        }

        // 3) Make NOT NULL (safe even if already NOT NULL)
        DB::statement('ALTER TABLE country_itinerary MODIFY country_id BIGINT UNSIGNED NOT NULL');

        // 4) Add FK if not present
        if (!$this->foreignKeyExists('country_itinerary', 'ci_country_id_fk')) {
            Schema::table('country_itinerary', function (Blueprint $table) {
                $table->foreign('country_id', 'ci_country_id_fk')
                    ->references('id')->on('countries')
                    ->onDelete('cascade');
            });
        }

        // 5) Indexes/uniques and drop old column, but only if 'country' exists
        if (Schema::hasColumn('country_itinerary', 'country')) {
            // Try to drop old unique/index; ignore if missing
            $this->dropIndexIfExists('country_itinerary', 'country_itinerary_itinerary_id_country_unique');
            $this->dropIndexIfExists('country_itinerary', 'country_itinerary_country_index');

            Schema::table('country_itinerary', function (Blueprint $table) {
                $table->dropColumn('country');
            });
        }

        // Ensure new unique exists (idempotent)
        $this->createUniqueIfMissing(
            'country_itinerary',
            'country_itinerary_itinerary_id_country_id_unique',
            ['itinerary_id', 'country_id']
        );
    }

    // Helper: check FK existence
    private function foreignKeyExists(string $table, string $fkName): bool
    {
        $db = DB::getDatabaseName();
        $count = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $db)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $fkName)
            ->count();
        return $count > 0;
    }

    // Helper: drop index if exists
    private function dropIndexIfExists(string $table, string $index): void
    {
        try {
            DB::statement("DROP INDEX `$index` ON `$table`");
        } catch (\Throwable $e) {
            // ignore
        }
    }

    // Helper: create unique if missing
    private function createUniqueIfMissing(string $table, string $index, array $columns): void
    {
        $db = DB::getDatabaseName();
        $exists = DB::table('information_schema.statistics')
            ->where('TABLE_SCHEMA', $db)
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $index)
            ->exists();

        if (!$exists) {
            Schema::table($table, function (Blueprint $t) use ($columns, $index) {
                $t->unique($columns, $index);
            });
        }
    }
};
