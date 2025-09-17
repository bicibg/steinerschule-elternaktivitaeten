<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action_type'); // e.g., 'year_reset', 'user_deletion', 'bulk_import', etc.
            $table->string('action_name'); // Human-readable name
            $table->foreignId('performed_by')->constrained('users');
            $table->string('performed_by_name');
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable(); // Store action-specific data as JSON
            $table->text('description')->nullable();
            $table->enum('severity', ['info', 'warning', 'critical'])->default('info');
            $table->timestamps();

            $table->index('action_type');
            $table->index('performed_by');
            $table->index('created_at');
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};