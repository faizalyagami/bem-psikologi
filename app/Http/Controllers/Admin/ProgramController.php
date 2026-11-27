<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Program;
use App\Support\ActivityLogger;
use App\Support\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-settings');
    }

    public function index(Request $request)
    {
        $query = Program::with(['department', 'user'])->orderBy('sort_order')->latest();

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.programs.index', compact('programs', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.programs.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:programs,slug'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:planned,ongoing,completed,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = ImageUploader::upload($request->file('featured_image'), 'programs');
        }

        $program = Program::create($data);
        ActivityLogger::log('program.created', $program, ['title' => $program->title]);

        return redirect()->route('admin.programs.index')
            ->with('success', "Program \"{$program->title}\" berhasil ditambahkan.");
    }

    public function edit(Program $program)
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.programs.edit', compact('program', 'departments'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:programs,slug,' . $program->id],
            'department_id' => ['nullable', 'exists:departments,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:planned,ongoing,completed,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = ImageUploader::upload($request->file('featured_image'), 'programs', $program->featured_image);
        }

        $program->update($data);
        ActivityLogger::log('program.updated', $program, ['title' => $program->title]);

        return redirect()->route('admin.programs.index')
            ->with('success', "Program \"{$program->title}\" berhasil diperbarui.");
    }

    public function destroy(Program $program)
    {
        $title = $program->title;
        ImageUploader::delete($program->featured_image);
        $program->delete();
        ActivityLogger::log('program.deleted', null, ['title' => $title]);

        return redirect()->route('admin.programs.index')
            ->with('success', "Program \"{$title}\" berhasil dihapus.");
    }
}
