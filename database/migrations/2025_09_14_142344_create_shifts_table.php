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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulletin_post_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->string('time');
            $table->integer('needed')->default(1);
            $table->integer('offline_filled')->default(0);
            $table->timestamps();

            $table->index('bulletin_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
