<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Support\ActivityLogger;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-categories');
    }

    public function index()
    {
        $categories = Category::with('parent')->withCount('posts')->orderBy('name')->get();
        return view('admin.content.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::orderBy('name')->get();
        return view('admin.content.categories.create', compact('parents'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $category = Category::create($data);
        ActivityLogger::log('category.created', $category);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori {$category->name} berhasil ditambahkan.");
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.content.categories.edit', compact('category', 'parents'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $category->update($data);
        ActivityLogger::log('category.updated', $category);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori {$category->name} berhasil diperbarui.");
    }

    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();
        ActivityLogger::log('category.deleted', null, ['name' => $name]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori {$name} berhasil dihapus.");
    }
}
