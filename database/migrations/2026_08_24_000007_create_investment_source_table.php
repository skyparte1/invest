<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_source', function (Blueprint $table) {
            $table->foreignId('investment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('source_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('sort_order')->default(0);

            $table->unique(['investment_id', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_source');
    }
};
