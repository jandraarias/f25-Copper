<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 0) Ensure target columns exist on users (your repo already does this; kept for idempotency)
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone_number');
            }
        });

        // 1) Backfill users.<fields> from travelers if users.<field> is NULL
        // Works across drivers by doing row-by-row updates (small data set). If very large, batch/chunk.
        $rows = DB::table('travelers')
            ->join('users', 'users.id', '=', 'travelers.user_id')
            ->select('users.id as user_id', 'travelers.phone_number', 'travelers.date_of_birth')
            ->get();

        foreach ($rows as $r) {
            $update = [];
            if (is_null(DB::table('users')->where('id', $r->user_id)->value('phone_number')) && !empty($r->phone_number)) {
                $update['phone_number'] = $r->phone_number;
            }
            if (is_null(DB::table('users')->where('id', $r->user_id)->value('date_of_birth')) && !empty($r->date_of_birth)) {
                $update['date_of_birth'] = $r->date_of_birth;
            }
            if ($update) {
                DB::table('users')->where('id', $r->user_id)->update($update);
            }
        }

        // 2) Drop duplicate PII columns from travelers
        Schema::table('travelers', function (Blueprint $table) {
            if (Schema::hasColumn('travelers', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('travelers', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('travelers', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
        });
    }

    public function down(): void
    {
        // Re-create columns on travelers
        Schema::table('travelers', function (Blueprint $table) {
            if (! Schema::hasColumn('travelers', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (! Schema::hasColumn('travelers', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('email');
            }
            if (! Schema::hasColumn('travelers', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone_number');
            }
        });

        // Best-effort backfill travelers from users
        $rows = DB::table('travelers')
            ->join('users', 'users.id', '=', 'travelers.user_id')
            ->select('travelers.id as traveler_id', 'users.email', 'users.phone_number', 'users.date_of_birth')
            ->get();

        foreach ($rows as $r) {
            DB::table('travelers')->where('id', $r->traveler_id)->update([
                'email'         => $r->email,
                'phone_number'  => $r->phone_number,
                'date_of_birth' => $r->date_of_birth,
            ]);
        }
    }
};
