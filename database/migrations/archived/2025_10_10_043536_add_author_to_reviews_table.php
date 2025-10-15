<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $t) {
            // add if missing
            if (!Schema::hasColumn('reviews', 'author')) {
                $t->string('author')->nullable()->after('source');
            }
            if (!Schema::hasColumn('reviews', 'published_at_date')) {
                $t->dateTime('published_at_date')->nullable()->after('text');
            }
            if (!Schema::hasColumn('reviews', 'fetched_at')) {
                $t->dateTime('fetched_at')->nullable()->after('published_at_date');
            }
            // ensure meta exists and is JSON (skip if you already have it)
            if (!Schema::hasColumn('reviews', 'meta')) {
                $t->json('meta')->nullable()->after('fetched_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $t) {
            if (Schema::hasColumn('reviews', 'author')) $t->dropColumn('author');
            if (Schema::hasColumn('reviews', 'published_at_date')) $t->dropColumn('published_at_date');
            if (Schema::hasColumn('reviews', 'fetched_at')) $t->dropColumn('fetched_at');
            if (Schema::hasColumn('reviews', 'meta')) $t->dropColumn('meta');
        });
    }
};
