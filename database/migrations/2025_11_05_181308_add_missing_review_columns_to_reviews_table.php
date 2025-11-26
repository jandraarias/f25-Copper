<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'review_id')) {
                    $table->unsignedBigInteger('review_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('reviews', 'place_id')) {
                    $table->unsignedBigInteger('place_id')->nullable()->after('review_id');
                }
                if (!Schema::hasColumn('reviews', 'place_name')) {
                    $table->string('place_name')->nullable()->after('place_id');
                }
                if (!Schema::hasColumn('reviews', 'reviewer_name')) {
                    $table->string('reviewer_name')->nullable()->after('place_name');
                }
                if (!Schema::hasColumn('reviews', 'rating')) {
                    $table->decimal('rating', 3, 1)->nullable()->after('reviewer_name');
                }
                if (!Schema::hasColumn('reviews', 'review_text')) {
                    $table->text('review_text')->nullable()->after('rating');
                }
                if (!Schema::hasColumn('reviews', 'reviewed_at')) {
                    $table->date('reviewed_at')->nullable()->after('review_text');
                }
                if (!Schema::hasColumn('reviews', 'review_keywords')) {
                    $table->text('review_keywords')->nullable()->after('reviewed_at');
                }
            });
        } else {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('review_id')->nullable();
                $table->unsignedBigInteger('place_id')->nullable();
                $table->string('place_name')->nullable();
                $table->string('reviewer_name')->nullable();
                $table->decimal('rating', 3, 1)->nullable();
                $table->text('review_text')->nullable();
                $table->date('reviewed_at')->nullable();
                $table->text('review_keywords')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally empty to avoid removing teammates' columns on rollback.
    }
};