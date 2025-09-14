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
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->string('author_name');
            $table->text('body');
            $table->string('ip_hash', 64)->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->string('hidden_reason')->nullable();
            $table->timestamps();

            $table->index('activity_id');
            $table->index('is_hidden');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};