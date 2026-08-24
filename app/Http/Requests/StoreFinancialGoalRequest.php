<?php

namespace App\Http\Requests;

class StoreFinancialGoalRequest extends FinancialGoalRequest
{
    public function rules(): array
    {
        return $this->goalRules(['required', 'date', 'after_or_equal:today']);
    }
}
