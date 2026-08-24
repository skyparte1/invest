<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Investment;
use App\Models\Source;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = [
            'published_contents' => Content::published()->count(), 'draft_contents' => Content::where('is_published', false)->count(),
            'published_investments' => Investment::published()->count(), 'draft_investments' => Investment::where('is_published', false)->count(),
            'sources' => Source::count(),
        ];

        return view('admin.dashboard', compact('counts'));
    }
}
