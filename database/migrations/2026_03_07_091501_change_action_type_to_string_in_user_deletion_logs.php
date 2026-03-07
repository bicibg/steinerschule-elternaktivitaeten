<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_deletion_logs', function (Blueprint $table) {
            $table->string('action_type')->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_deletion_logs', function (Blueprint $table) {
            $table->enum('action_type', ['deactivated', 'reactivated', 'anonymized'])->change();
        });
    }
};
