<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    protected $fillable = [
        'submission_id', 'investment_id', 'investment_name', 'initial_amount',
        'monthly_contribution', 'annual_rate', 'term_months', 'final_balance',
    ];
}
