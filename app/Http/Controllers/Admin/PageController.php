<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use App\Support\ActivityLogger;
use App\Support\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-pages');
    }

    public function index(Request $request)
    {
        $query = Page::with(['translations', 'user'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('translations', fn($q) => $q->where('title', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pages = $query->paginate(10)->withQueryString();

        return view('admin.content.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.content.pages.create');
    }

    public function store(StorePageRequest $request)
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = ImageUploader::upload($request->file('featured_image'), 'pages');
        }

        $page = Page::create([
            'user_id' => auth()->id(),
            'featured_image' => $imagePath,
            'status' => $data['status'],
        ]);

        // Terjemahan ID
        $page->translations()->create([
            'locale' => 'id',
            'title' => $data['id']['title'],
            'slug' => $data['id']['slug'] ?: Str::slug($data['id']['title']),
            'content' => $data['id']['content'],
            'meta_title' => $data['id']['meta_title'] ?? null,
            'meta_description' => $data['id']['meta_description'] ?? null,
        ]);

        // Terjemahan EN
        if (!empty($data['en']['title'])) {
            $page->translations()->create([
                'locale' => 'en',
                'title' => $data['en']['title'],
                'slug' => $data['en']['slug'] ?: Str::slug($data['en']['title']),
                'content' => $data['en']['content'] ?? '',
                'meta_title' => $data['en']['meta_title'] ?? null,
                'meta_description' => $data['en']['meta_description'] ?? null,
            ]);
        }

        ActivityLogger::log('page.created', $page, ['title' => $page->translation('id')->title]);

        return redirect()->route('admin.pages.index')
            ->with('success', "Halaman \"{$page->translation('id')->title}\" berhasil ditambahkan.");
    }

    public function edit(Page $page)
    {
        $page->load('translations');
        return view('admin.content.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $data = $request->validated();

        $imagePath = $page->featured_image;
        if ($request->hasFile('featured_image')) {
            $imagePath = ImageUploader::upload($request->file('featured_image'), 'pages', $page->featured_image);
        }

        $page->update([
            'featured_image' => $imagePath,
            'status' => $data['status'],
        ]);

        $page->translations()->updateOrCreate(
            ['locale' => 'id'],
            [
                'title' => $data['id']['title'],
                'slug' => $data['id']['slug'] ?: Str::slug($data['id']['title']),
                'content' => $data['id']['content'],
                'meta_title' => $data['id']['meta_title'] ?? null,
                'meta_description' => $data['id']['meta_description'] ?? null,
            ]
        );

        if (!empty($data['en']['title'])) {
            $page->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'title' => $data['en']['title'],
                    'slug' => $data['en']['slug'] ?: Str::slug($data['en']['title']),
                    'content' => $data['en']['content'] ?? '',
                    'meta_title' => $data['en']['meta_title'] ?? null,
                    'meta_description' => $data['en']['meta_description'] ?? null,
                ]
            );
        } else {
            $page->translations()->where('locale', 'en')->delete();
        }

        ActivityLogger::log('page.updated', $page, ['title' => $page->translation('id')->title]);

        return redirect()->route('admin.pages.index')
            ->with('success', "Halaman \"{$page->translation('id')->title}\" berhasil diperbarui.");
    }

    public function destroy(Page $page)
    {
        $title = $page->translation('id')?->title ?? 'Halaman';
        ImageUploader::delete($page->featured_image);
        $page->delete();
        ActivityLogger::log('page.deleted', null, ['title' => $title]);

        return redirect()->route('admin.pages.index')
            ->with('success', "Halaman \"{$title}\" berhasil dihapus.");
    }
}
