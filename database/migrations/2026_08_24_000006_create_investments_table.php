<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('description');
            $table->string('risk_level', 20);
            $table->longText('risk_description');
            $table->longText('liquidity_description');
            $table->longText('profitability_description');
            $table->longText('taxation_description')->nullable();
            $table->longText('protection_description')->nullable();
            $table->longText('advantages')->nullable();
            $table->longText('points_of_attention')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'investment_category_id', 'sort_order']);
            $table->index(['is_published', 'risk_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
