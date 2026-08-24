<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $firstName = str($request->user()->name)->trim()->before(' ')->toString();

        return view('dashboard.index', compact('firstName'));
    }
}
