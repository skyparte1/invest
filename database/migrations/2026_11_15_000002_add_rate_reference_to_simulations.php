<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('simulations', fn (Blueprint $table) => $table->json('rate_reference')->nullable());
    }

    public function down(): void
    {
        Schema::table('simulations', fn (Blueprint $table) => $table->dropColumn('rate_reference'));
    }
};
