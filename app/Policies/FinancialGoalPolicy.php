<?php

namespace App\Policies;

use App\Models\FinancialGoal;
use App\Models\User;

class FinancialGoalPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, FinancialGoal $financialGoal): bool
    {
        return $this->owns($user, $financialGoal);
    }

    public function update(User $user, FinancialGoal $financialGoal): bool
    {
        return $this->owns($user, $financialGoal);
    }

    public function delete(User $user, FinancialGoal $financialGoal): bool
    {
        return $this->owns($user, $financialGoal);
    }

    private function owns(User $user, FinancialGoal $financialGoal): bool
    {
        return $financialGoal->user_id === $user->id;
    }
}
