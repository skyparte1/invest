<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();
        $categorySlug = $request->string('categoria')->trim()->toString();
        $selectedCategory = $categories->firstWhere('slug', $categorySlug);

        $contents = Content::query()
            ->published()
            ->with('category')
            ->when($selectedCategory, fn ($query) => $query->whereBelongsTo($selectedCategory))
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('learn.index', compact('categories', 'contents', 'selectedCategory'));
    }

    public function show(string $slug): View
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

        $bodyHtml = Str::markdown($content->body, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('learn.show', compact('content', 'relatedContents', 'bodyHtml'));
    }
}
