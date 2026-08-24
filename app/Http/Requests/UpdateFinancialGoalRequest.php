<?php

namespace App\Http\Requests;

class UpdateFinancialGoalRequest extends FinancialGoalRequest
{
    public function rules(): array
    {
        return $this->goalRules(['required', 'date']);
    }
}
