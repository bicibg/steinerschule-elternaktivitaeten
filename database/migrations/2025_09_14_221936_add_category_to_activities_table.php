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
        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->enum('category', ['anlass', 'haus_umgebung_taskforces', 'produktion', 'organisation', 'verkauf'])
                ->nullable()
                ->after('status')
                ->comment('Activity category: anlass (Events), haus_umgebung_taskforces (House, Environment & Taskforces), produktion (Production), organisation (Organization), verkauf (Sales)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};