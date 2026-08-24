<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContentProgressController extends Controller
{
    public function store(Request $request, Content $content): RedirectResponse
    {
        abort_unless($content->is_published, 404);
        $request->user()->contentProgress()->updateOrCreate(['content_id' => $content->id], ['completed_at' => now()]);

        return back()->with('status', 'Conteúdo marcado como concluído.');
    }

    public function destroy(Request $request, Content $content): RedirectResponse
    {
        abort_unless($content->is_published, 404);
        $request->user()->contentProgress()->where('content_id', $content->id)->delete();

        return back()->with('status', 'Conclusão desfeita.');
    }
}
