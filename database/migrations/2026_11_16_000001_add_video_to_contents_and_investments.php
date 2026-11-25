<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['contents', 'investments'] as $table) {
            Schema::table($table, fn (Blueprint $schema) => $schema->json('video')->nullable());
        }
    }

    public function down(): void
    {
        foreach (['contents', 'investments'] as $table) {
            Schema::table($table, fn (Blueprint $schema) => $schema->dropColumn('video'));
        }
    }
};
