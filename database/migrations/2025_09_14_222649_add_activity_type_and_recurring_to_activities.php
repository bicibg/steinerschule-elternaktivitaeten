<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->enum('activity_type', ['shift_based', 'production', 'meeting', 'flexible_help'])
                ->default('shift_based')
                ->after('category')
                ->comment('Type of activity: shift_based (specific shifts), production (ongoing work), meeting (regular meetings), flexible_help (open participation)');

            $table->string('recurring_pattern')->nullable()
                ->after('activity_type')
                ->comment('For meetings: e.g., "every Thursday", "weekly", "monthly"');

            $table->boolean('show_in_calendar')->default(true)
                ->after('recurring_pattern')
                ->comment('Whether to display this activity in the calendar');

            $table->text('participation_note')->nullable()
                ->after('description')
                ->comment('Additional note about participation (e.g., "Work from home possible", "Join us anytime")');
        });

        // Make shift needed count nullable for flexible activities
        Schema::table('shifts', function (Blueprint $table) {
            $table->integer('needed')->nullable()->change();
            $table->boolean('flexible_capacity')->default(false)
                ->after('needed')
                ->comment('True if any number of helpers is welcome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['activity_type', 'recurring_pattern', 'show_in_calendar', 'participation_note']);
        });

        Schema::table('shifts', function (Blueprint $table) {
            $table->integer('needed')->nullable(false)->change();
            $table->dropColumn('flexible_capacity');
        });
    }
};