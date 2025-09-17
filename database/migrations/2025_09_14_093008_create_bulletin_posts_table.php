<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulletin_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->datetime('start_at');
            $table->datetime('end_at')->nullable();
            $table->string('location');
            $table->string('contact_name');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('slug')->unique();
            $table->enum('status', ['published', 'archived'])->default('published');
            $table->string('edit_token', 64)->unique();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('start_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulletin_posts');
    }
};