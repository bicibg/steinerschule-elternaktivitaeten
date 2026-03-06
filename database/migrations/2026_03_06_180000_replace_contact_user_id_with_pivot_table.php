<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create polymorphic pivot table for contact users
        Schema::create('contact_users', function (Blueprint $table) {
            $table->id();
            $table->morphs('contactable'); // contactable_type + contactable_id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['contactable_type', 'contactable_id', 'user_id'], 'contact_users_unique');
        });

        // Remove old single FK columns
        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contact_user_id');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contact_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('bulletin_posts', function (Blueprint $table) {
            $table->foreignId('contact_user_id')->nullable()->after('contact_email')->constrained('users')->nullOnDelete();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('contact_user_id')->nullable()->after('contact_phone')->constrained('users')->nullOnDelete();
        });

        Schema::dropIfExists('contact_users');
    }
};
