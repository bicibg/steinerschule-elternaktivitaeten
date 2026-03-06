<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bulletin_posts MODIFY COLUMN status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'published'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE bulletin_posts SET status = 'published' WHERE status = 'draft'");
            DB::statement("ALTER TABLE bulletin_posts MODIFY COLUMN status ENUM('published', 'archived') NOT NULL DEFAULT 'published'");
        }
    }
};
