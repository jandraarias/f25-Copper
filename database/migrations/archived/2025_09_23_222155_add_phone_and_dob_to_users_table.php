<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone_number');
            }
        });

        // One-time backfill from travelers -> users (only if the columns still exist)
        if (Schema::hasTable('travelers')) {
            $travelerHasPhone = Schema::hasColumn('travelers', 'phone_number');
            $travelerHasDob   = Schema::hasColumn('travelers', 'date_of_birth');

            if ($travelerHasPhone || $travelerHasDob) {
                $travelers = DB::table('travelers')
                    ->select('user_id', 
                        $travelerHasPhone ? 'phone_number' : DB::raw('NULL as phone_number'),
                        $travelerHasDob ? 'date_of_birth' : DB::raw('NULL as date_of_birth')
                    )
                    ->whereNotNull('user_id')
                    ->get();

                foreach ($travelers as $t) {
                    if ($travelerHasPhone && $t->phone_number !== null) {
                        DB::table('users')
                            ->where('id', $t->user_id)
                            ->whereNull('phone_number')
                            ->update(['phone_number' => $t->phone_number]);
                    }

                    if ($travelerHasDob && $t->date_of_birth !== null) {
                        DB::table('users')
                            ->where('id', $t->user_id)
                            ->whereNull('date_of_birth')
                            ->update(['date_of_birth' => $t->date_of_birth]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
            if (Schema::hasColumn('users', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
        });
    }
};
