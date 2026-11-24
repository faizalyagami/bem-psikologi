<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['translations', 'category', 'user'])
            ->where('status', 'published')
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('q')) {
            $s = $request->q;
            $query->whereHas('translations', fn($q) => $q->where('title', 'like', "%$s%"));
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('frontend.posts.index', compact('posts', 'categories'));
    }

    public function show($locale, $slug)
    {
        $post = Post::with(['translations', 'category', 'user', 'tags'])
            ->where('status', 'published')
            ->whereHas('translations', fn($q) => $q->where('slug', $slug))
            ->firstOrFail();

        $translation = $post->translations->firstWhere('locale', app()->getLocale())
            ?? $post->translations->firstWhere('locale', 'id');

        $related = Post::with(['translations', 'category'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->limit(3)
            ->get();

        return view('frontend.posts.show', compact('post', 'translation', 'related'));
    }
}
