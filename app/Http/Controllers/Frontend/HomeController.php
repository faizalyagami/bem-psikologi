<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $latestPosts = Post::with(['translations', 'category'])
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(6)
            ->get();

        $upcomingEvents = Event::with('translations')
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        $aboutPage = Page::with('translations')
            ->where('status', 'published')
            ->whereHas('translations', fn($q) => $q->where('slug', 'tentang-bem'))
            ->first();

        return view('frontend.home', compact('latestPosts', 'upcomingEvents', 'aboutPage'));
    }
}
