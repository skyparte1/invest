<?php

namespace App\Services;

use App\Models\Content;
use App\Models\User;

class LearningProgressService
{
    public function summary(User $user): array
    {
        $total = Content::query()->published()->count();
        $completed = $user->contentProgress()->whereHas('content', fn ($query) => $query->published())->count();

        return ['total' => $total, 'completed' => $completed, 'percentage' => $total === 0 ? 0 : (int) round($completed * 100 / $total)];
    }
}
