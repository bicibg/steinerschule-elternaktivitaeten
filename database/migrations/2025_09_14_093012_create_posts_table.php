<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulletin_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->string('ip_hash', 64)->nullable();
            $table->enum('deletion_reason', [
                'year_archived',
                'spam',
                'inappropriate',
                'user_requested',
                'duplicate'
            ])->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('bulletin_post_id');
            $table->index('deleted_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};