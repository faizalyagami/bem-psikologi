<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-settings'); // Atau permission khusus
    }

    public function index()
    {
        $departments = Department::withCount('members')->orderBy('sort_order')->get();
        return view('admin.organization.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:departments,slug'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $dept = Department::create($data);
        ActivityLogger::log('department.created', $dept, ['name' => $dept->name]);

        return back()->with('success', "Departemen {$dept->name} ditambahkan.");
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:departments,slug,' . $department->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $department->update($data);
        ActivityLogger::log('department.updated', $department, ['name' => $department->name]);

        return back()->with('success', "Departemen {$department->name} diperbarui.");
    }

    public function destroy(Department $department)
    {
        $name = $department->name;
        $department->delete();
        ActivityLogger::log('department.deleted', null, ['name' => $name]);

        return back()->with('success', "Departemen {$name} dihapus.");
    }
}
