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
        Schema::create('user_deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('user_name');
            $table->string('user_email');
            $table->enum('action_type', ['deactivated', 'reactivated', 'anonymized']);
            $table->integer('performed_by');
            $table->string('performed_by_name');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('performed_by');
            $table->index('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_deletion_logs');
    }
};