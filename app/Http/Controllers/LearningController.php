<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Support\SafeMarkdown;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();
        $categorySlug = $request->string('categoria')->trim()->toString();
        $selectedCategory = $categories->firstWhere('slug', $categorySlug);

        $completedIds = $request->user()?->contentProgress()->pluck('content_id') ?? collect();
        $status = $request->user() && in_array($request->string('status')->toString(), ['concluido', 'pendente'], true)
            ? $request->string('status')->toString() : null;

        $contents = Content::query()
            ->published()
            ->with('category')
            ->when($selectedCategory, fn ($query) => $query->whereBelongsTo($selectedCategory))
            ->when($status === 'concluido', fn ($query) => $query->whereIn('id', $completedIds))
            ->when($status === 'pendente', fn ($query) => $query->whereNotIn('id', $completedIds))
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('learn.index', compact('categories', 'contents', 'selectedCategory', 'completedIds', 'status'));
    }

    public function show(Request $request, string $slug): View
    {
        $content = Content::query()
            ->published()
            ->with(['category', 'sources'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedContents = Content::query()
            ->published()
            ->whereBelongsTo($content->category)
            ->whereKeyNot($content->getKey())
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->limit(3)
            ->get();

        $bodyHtml = SafeMarkdown::render($content->body);

        $isCompleted = $request->user()?->contentProgress()->where('content_id', $content->id)->exists() ?? false;

        return view('learn.show', compact('content', 'relatedContents', 'bodyHtml', 'isCompleted'));
    }
}
