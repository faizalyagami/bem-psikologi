<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with(['department', 'user'])->latest();

        if ($request->filled('department')) {
            $query->whereHas('department', fn($q) => $q->where('slug', $request->department));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->paginate(9)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('frontend.programs.index', compact('programs', 'departments'));
    }

    public function show($locale, $slug)
    {
        $program = Program::with(['department', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Program::where('department_id', $program->department_id)
            ->where('id', '!=', $program->id)
            ->limit(3)
            ->get();

        return view('frontend.programs.show', compact('program', 'related'));
    }
}
