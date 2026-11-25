<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $upcoming = Event::with('translations')
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        $past = Event::with('translations')
            ->where('status', 'published')
            ->where('start_date', '<', now())
            ->orderByDesc('start_date')
            ->limit(10)
            ->get();

        return view('frontend.events.index', compact('upcoming', 'past'));
    }

    public function show($locale, $slug)
    {
        $event = Event::with(['translations', 'user'])
            ->where('status', 'published')
            ->whereHas('translations', fn($q) => $q->where('slug', $slug))
            ->firstOrFail();

        $translation = $event->translations->firstWhere('locale', app()->getLocale())
            ?? $event->translations->firstWhere('locale', 'id');

        return view('frontend.events.show', compact('event', 'translation'));
    }
}
