<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('submission_id');
            $table->unique(['user_id', 'submission_id']);
            $table->foreignId('investment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('investment_name')->nullable();
            $table->double('initial_amount');
            $table->double('monthly_contribution');
            $table->double('annual_rate');
            $table->unsignedSmallInteger('term_months');
            $table->double('final_balance');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
