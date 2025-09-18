<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\SchoolEvent;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_events', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('title');
        });

        // Generate slugs for existing events with random suffix
        SchoolEvent::whereNull('slug')->get()->each(function ($event) {
            $event->slug = Str::slug($event->title) . '-' . Str::random(6);
            $event->save();
        });

        // Make slug not nullable after populating
        Schema::table('school_events', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_events', function (Blueprint $table) {
            $table->dropIndex(['slug']); // Drop the unique index first
        });

        Schema::table('school_events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};