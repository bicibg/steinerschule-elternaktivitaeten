<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->string('author_name');
            $table->text('body');
            $table->string('ip_hash');
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();

            $table->index('activity_id');
            $table->index('is_hidden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_posts');
    }
};