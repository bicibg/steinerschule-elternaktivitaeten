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
        if (!Schema::hasColumn('shift_volunteers', 'email')) {
            Schema::table('shift_volunteers', function (Blueprint $table) {
                $table->string('email')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_volunteers', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};