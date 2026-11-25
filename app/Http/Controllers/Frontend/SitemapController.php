<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Page;
use App\Models\Post;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')->with('translations')->get();
        $pages = Page::where('status', 'published')->with('translations')->get();
        $events = Event::where('status', 'published')->with('translations')->get();

        $urls = collect();

        foreach (['id', 'en'] as $locale) {
            $urls->push(['loc' => route('home', ['locale' => $locale]), 'priority' => '1.0']);

            foreach ($posts as $post) {
                $tr = $post->translations->firstWhere('locale', $locale);
                if ($tr) $urls->push(['loc' => route('frontend.posts.show', ['locale' => $locale, 'slug' => $tr->slug]), 'priority' => '0.8']);
            }
            foreach ($pages as $page) {
                $tr = $page->translations->firstWhere('locale', $locale);
                if ($tr) $urls->push(['loc' => route('frontend.pages.show', ['locale' => $locale, 'slug' => $tr->slug]), 'priority' => '0.6']);
            }
            foreach ($events as $event) {
                $tr = $event->translations->firstWhere('locale', $locale);
                if ($tr) $urls->push(['loc' => route('frontend.events.show', ['locale' => $locale, 'slug' => $tr->slug]), 'priority' => '0.6']);
            }
        }

        return response()
            ->view('frontend.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
