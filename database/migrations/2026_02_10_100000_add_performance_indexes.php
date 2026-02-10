<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shift_volunteers', function (Blueprint $table) {
            $table->unique(['shift_id', 'user_id'], 'shift_volunteers_shift_user_unique');
        });

        Schema::table('school_events', function (Blueprint $table) {
            $table->index('start_date');
            $table->index('event_type');
        });

        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->index('end_at');
            $table->index('category');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->index(['starts_at', 'expires_at'], 'announcements_active_range_index');
        });
    }

    public function down(): void
    {
        Schema::table('shift_volunteers', function (Blueprint $table) {
            $table->dropUnique('shift_volunteers_shift_user_unique');
        });

        Schema::table('school_events', function (Blueprint $table) {
            $table->dropIndex(['start_date']);
            $table->dropIndex(['event_type']);
        });

        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->dropIndex(['end_at']);
            $table->dropIndex(['category']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex('announcements_active_range_index');
        });
    }
};
