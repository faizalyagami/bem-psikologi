<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-settings');
    }

    public function index()
    {
        $positions = Position::withCount('members')->orderBy('sort_order')->get();
        return view('admin.organization.positions.index', compact('positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:positions,slug'],
            'level' => ['required', 'in:pimpinan,pengurus_inti,kepala_departemen,staf'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $pos = Position::create($data);
        ActivityLogger::log('position.created', $pos, ['name' => $pos->name]);

        return back()->with('success', "Jabatan {$pos->name} ditambahkan.");
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:positions,slug,' . $position->id],
            'level' => ['required', 'in:pimpinan,pengurus_inti,kepala_departemen,staf'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $position->update($data);
        ActivityLogger::log('position.updated', $position, ['name' => $position->name]);

        return back()->with('success', "Jabatan {$position->name} diperbarui.");
    }

    public function destroy(Position $position)
    {
        $name = $position->name;
        $position->delete();
        ActivityLogger::log('position.deleted', null, ['name' => $name]);

        return back()->with('success', "Jabatan {$name} dihapus.");
    }
}
