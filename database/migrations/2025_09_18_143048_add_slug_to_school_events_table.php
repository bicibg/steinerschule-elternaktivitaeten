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

        // Generate slugs for existing events
        SchoolEvent::whereNull('slug')->get()->each(function ($event) {
            $baseSlug = Str::slug($event->title);
            $slug = $baseSlug;
            $counter = 1;

            while (SchoolEvent::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $event->slug = $slug;
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
            $table->dropColumn('slug');
        });
    }
};