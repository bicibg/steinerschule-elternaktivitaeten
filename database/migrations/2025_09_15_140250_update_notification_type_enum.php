<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN, so we need to recreate the table
        // First, rename the old table
        Schema::rename('notifications', 'notifications_old');

        // Create new table with updated enum
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'announcement', 'reminder', 'urgent'])->default('info');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_priority')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Copy data from old table, mapping old types to new ones
        DB::statement("
            INSERT INTO notifications
            SELECT
                id,
                title,
                message,
                CASE
                    WHEN type = 'error' THEN 'urgent'
                    WHEN type = 'warning' THEN 'reminder'
                    WHEN type = 'success' THEN 'announcement'
                    ELSE 'info'
                END as type,
                is_active,
                is_priority,
                starts_at,
                expires_at,
                created_by,
                created_at,
                updated_at
            FROM notifications_old
        ");

        // Drop old table
        Schema::dropIfExists('notifications_old');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('notifications', 'notifications_old');

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'error'])->default('info');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_priority')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        DB::statement("
            INSERT INTO notifications
            SELECT
                id,
                title,
                message,
                CASE
                    WHEN type = 'urgent' THEN 'error'
                    WHEN type = 'reminder' THEN 'warning'
                    WHEN type = 'announcement' THEN 'success'
                    ELSE 'info'
                END as type,
                is_active,
                is_priority,
                starts_at,
                expires_at,
                created_by,
                created_at,
                updated_at
            FROM notifications_old
        ");

        Schema::dropIfExists('notifications_old');
    }
};