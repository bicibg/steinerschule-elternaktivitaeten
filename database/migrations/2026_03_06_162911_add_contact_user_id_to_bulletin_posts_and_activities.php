<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->foreignId('contact_user_id')->nullable()->after('contact_email')->constrained('users')->nullOnDelete();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('contact_user_id')->nullable()->after('contact_phone')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->dropForeign(['contact_user_id']);
            $table->dropColumn('contact_user_id');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['contact_user_id']);
            $table->dropColumn('contact_user_id');
        });
    }
};
