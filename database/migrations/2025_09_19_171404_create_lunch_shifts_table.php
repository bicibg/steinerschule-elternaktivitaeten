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
        Schema::create('lunch_shifts', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Each day has one lunch shift
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cook_name')->nullable(); // Name for display (could be offline assignment)
            $table->integer('expected_meals')->default(0); // Expected number of meals
            $table->text('notes')->nullable(); // Special notes for the day
            $table->boolean('is_filled')->default(false);
            $table->timestamps();

            // Indexes for quick lookups
            $table->index('date');
            $table->index('is_filled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lunch_shifts');
    }
};