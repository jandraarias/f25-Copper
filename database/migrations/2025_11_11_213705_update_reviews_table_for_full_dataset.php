<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {

            // Reviewer metadata
            if (!Schema::hasColumn('reviews', 'reviewer_id')) {
                $table->string('reviewer_id')->nullable()->after('place_name');
            }

            if (!Schema::hasColumn('reviews', 'reviewer_profile')) {
                $table->string('reviewer_profile')->nullable()->after('reviewer_id');
            }

            // Translated text
            if (!Schema::hasColumn('reviews', 'text_translated')) {
                $table->text('text_translated')->nullable()->after('text');
            }

            // Owner response translated
            if (!Schema::hasColumn('reviews', 'owner_response_translated')) {
                $table->text('owner_response_translated')->nullable()->after('owner_response');
            }

            // Google’s actual timestamp (your base migration already has published_at)
            if (!Schema::hasColumn('reviews', 'published_at')) {
                // Only add if missing — but do NOT "after" anything risky
                $table->timestamp('published_at')->nullable();
            }

            // Experience details JSON (place AFTER corrected publish date field)
            if (!Schema::hasColumn('reviews', 'experience_details')) {
                $table->json('experience_details')->nullable()->after('owner_response_publish_date');
            }

            // Review photos JSON (replace text version but only if not already JSON)
            if (!Schema::hasColumn('reviews', 'review_photos')) {
                $table->json('review_photos')->nullable()->after('experience_details');
            }

            // Additional metadata
            if (!Schema::hasColumn('reviews', 'meta')) {
                $table->json('meta')->nullable()->after('review_photos');
            }

        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {

            $drop = [
                'reviewer_id',
                'reviewer_profile',
                'text_translated',
                'owner_response_translated',
                'experience_details',
                'review_photos',
                'meta'
            ];

            foreach ($drop as $column) {
                if (Schema::hasColumn('reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
