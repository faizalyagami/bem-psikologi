<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Support\ActivityLogger;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-tags');
    }

    public function index()
    {
        $tags = Tag::withCount('posts')->orderBy('name')->paginate(20);
        return view('admin.content.tags.index', compact('tags'));
    }

    public function store(StoreTagRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $tag = Tag::create($data);
        ActivityLogger::log('tag.created', $tag);

        return back()->with('success', "Tag {$tag->name} berhasil ditambahkan.");
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $tag->update($data);
        ActivityLogger::log('tag.updated', $tag);

        return back()->with('success', "Tag {$tag->name} berhasil diperbarui.");
    }

    public function destroy(Tag $tag)
    {
        $name = $tag->name;
        $tag->delete();
        ActivityLogger::log('tag.deleted', null, ['name' => $name]);

        return back()->with('success', "Tag {$name} berhasil dihapus.");
    }
}
