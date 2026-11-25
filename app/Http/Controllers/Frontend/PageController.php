<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function about()
    {
        $page = Page::with('translations')
            ->where('status', 'published')
            ->whereHas('translations', fn($q) => $q->where('slug', 'tentang-bem'))
            ->first();

        $translation = $page?->translations->firstWhere('locale', app()->getLocale())
            ?? $page?->translations->firstWhere('locale', 'id');

        return view('frontend.pages.about', compact('page', 'translation'));
    }

    public function show($locale, $slug)
    {
        $page = Page::with('translations')
            ->where('status', 'published')
            ->whereHas('translations', fn($q) => $q->where('slug', $slug))
            ->firstOrFail();

        $translation = $page->translations->firstWhere('locale', app()->getLocale())
            ?? $page->translations->firstWhere('locale', 'id');

        return view('frontend.pages.show', compact('page', 'translation'));
    }
}
