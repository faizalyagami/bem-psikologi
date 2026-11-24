<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\ActivityLogger;
use App\Support\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-posts');
    }

    public function index(Request $request)
    {
        $query = Post::with(['translations', 'category', 'user'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('translations', fn($q) => $q->where('title', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $posts = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.content.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.content.posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        // Upload featured image
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = ImageUploader::upload($request->file('featured_image'), 'posts');
        }

        // Buat post
        $post = Post::create([
            'user_id' => auth()->id(),
            'category_id' => $data['category_id'] ?? null,
            'featured_image' => $imagePath,
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null,
        ]);

        // Simpan terjemahan ID (wajib)
        $post->translations()->create([
            'locale' => 'id',
            'title' => $data['id']['title'],
            'slug' => $data['id']['slug'] ?: Str::slug($data['id']['title']),
            'content' => $data['id']['content'],
            'excerpt' => $data['id']['excerpt'] ?? null,
            'meta_title' => $data['id']['meta_title'] ?? null,
            'meta_description' => $data['id']['meta_description'] ?? null,
        ]);

        // Simpan terjemahan EN (opsional)
        if (!empty($data['en']['title'])) {
            $post->translations()->create([
                'locale' => 'en',
                'title' => $data['en']['title'],
                'slug' => $data['en']['slug'] ?: Str::slug($data['en']['title']),
                'content' => $data['en']['content'] ?? '',
                'excerpt' => $data['en']['excerpt'] ?? null,
                'meta_title' => $data['en']['meta_title'] ?? null,
                'meta_description' => $data['en']['meta_description'] ?? null,
            ]);
        }

        // Sync tags
        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        ActivityLogger::log('post.created', $post, ['title' => $post->translation('id')->title]);

        return redirect()->route('admin.posts.index')
            ->with('success', "Berita \"{$post->translation('id')->title}\" berhasil ditambahkan.");
    }

    public function edit(Post $post)
    {
        $post->load(['translations', 'tags']);
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.content.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        // Upload featured image (ganti jika ada)
        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            $imagePath = ImageUploader::upload($request->file('featured_image'), 'posts', $post->featured_image);
        }

        $post->update([
            'category_id' => $data['category_id'] ?? null,
            'featured_image' => $imagePath,
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($data['published_at'] ?? $post->published_at ?? now()) : null,
        ]);

        // Update/create terjemahan ID
        $post->translations()->updateOrCreate(
            ['locale' => 'id'],
            [
                'title' => $data['id']['title'],
                'slug' => $data['id']['slug'] ?: Str::slug($data['id']['title']),
                'content' => $data['id']['content'],
                'excerpt' => $data['id']['excerpt'] ?? null,
                'meta_title' => $data['id']['meta_title'] ?? null,
                'meta_description' => $data['id']['meta_description'] ?? null,
            ]
        );

        // Update/create terjemahan EN
        if (!empty($data['en']['title'])) {
            $post->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'title' => $data['en']['title'],
                    'slug' => $data['en']['slug'] ?: Str::slug($data['en']['title']),
                    'content' => $data['en']['content'] ?? '',
                    'excerpt' => $data['en']['excerpt'] ?? null,
                    'meta_title' => $data['en']['meta_title'] ?? null,
                    'meta_description' => $data['en']['meta_description'] ?? null,
                ]
            );
        } else {
            // Hapus terjemahan EN jika title dikosongkan
            $post->translations()->where('locale', 'en')->delete();
        }

        $post->tags()->sync($data['tags'] ?? []);

        ActivityLogger::log('post.updated', $post, ['title' => $post->translation('id')->title]);

        return redirect()->route('admin.posts.index')
            ->with('success', "Berita \"{$post->translation('id')->title}\" berhasil diperbarui.");
    }

    public function destroy(Post $post)
    {
        $title = $post->translation('id')?->title ?? 'Berita';
        ImageUploader::delete($post->featured_image);
        $post->delete();

        ActivityLogger::log('post.deleted', null, ['title' => $title]);

        return redirect()->route('admin.posts.index')
            ->with('success', "Berita \"{$title}\" berhasil dihapus.");
    }
}
