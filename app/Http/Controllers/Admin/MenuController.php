<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-menus');
    }

    public function index()
    {
        $menus = Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.management.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:menus,id'],
            'route_name' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'target' => ['required', 'in:_self,_blank'],
            'location' => ['required', 'in:header,footer'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $menu = Menu::create($data);
        ActivityLogger::log('menu.created', $menu, ['title' => $menu->title]);

        return back()->with('success', "Menu {$menu->title} berhasil ditambahkan.");
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:menus,id'],
            'route_name' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'target' => ['required', 'in:_self,_blank'],
            'location' => ['required', 'in:header,footer'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if (($data['parent_id'] ?? null) == $menu->id) {
            return back()->with('error', 'Menu tidak bisa menjadi induk dirinya sendiri.');
        }

        $menu->update($data);
        ActivityLogger::log('menu.updated', $menu, ['title' => $menu->title]);

        return back()->with('success', "Menu {$menu->title} berhasil diperbarui.");
    }

    public function destroy(Menu $menu)
    {
        $title = $menu->title;
        $menu->children()->delete();
        $menu->delete();
        ActivityLogger::log('menu.deleted', null, ['title' => $title]);

        return back()->with('success', "Menu {$title} berhasil dihapus.");
    }
}
