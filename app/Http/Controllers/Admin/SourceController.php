<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SourceController extends Controller
{
    public function index(): View
    {
        return view('admin.sources.index', ['sources' => Source::withCount(['contents', 'investments'])->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.sources.form', ['source' => new Source]);
    }

    public function store(Request $request): RedirectResponse
    {
        Source::create($this->validated($request));

        return redirect()->route('admin.fontes.index')->with('status', 'Fonte criada.');
    }

    public function edit(Source $source): View
    {
        return view('admin.sources.form', compact('source'));
    }

    public function update(Request $request, Source $source): RedirectResponse
    {
        $source->update($this->validated($request, $source));

        return redirect()->route('admin.fontes.index')->with('status', 'Fonte atualizada.');
    }

    public function destroy(Source $source): RedirectResponse
    {
        if ($source->contents()->exists() || $source->investments()->exists()) {
            return back()->with('error', 'A fonte está vinculada e não pode ser excluída.');
        }
        $source->delete();

        return back()->with('status', 'Fonte excluída.');
    }

    private function validated(Request $request, ?Source $source = null): array
    {
        return $request->validate([
            'institution' => ['required', 'string', 'max:255'], 'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:2048', Rule::unique('sources')->ignore($source)],
            'publication_date' => ['nullable', 'date'], 'accessed_at' => ['required', 'date'],
        ]);
    }
}
