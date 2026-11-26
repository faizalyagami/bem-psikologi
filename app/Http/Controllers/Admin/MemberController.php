<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Member;
use App\Models\Position;
use App\Support\ActivityLogger;
use App\Support\ImageUploader;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-settings');
    }

    public function index(Request $request)
    {
        $query = Member::with(['department', 'position'])->orderBy('sort_order');

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        $members = $query->paginate(20)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.organization.members.index', compact('members', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('sort_order')->get();
        return view('admin.organization.members.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'linkedin' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = ImageUploader::upload($request->file('photo'), 'members');
        }

        $member = Member::create($data);
        ActivityLogger::log('member.created', $member, ['name' => $member->name]);

        return redirect()->route('admin.members.index')
            ->with('success', "Anggota {$member->name} ditambahkan.");
    }

    public function edit(Member $member)
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('sort_order')->get();
        return view('admin.organization.members.edit', compact('member', 'departments', 'positions'));
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'linkedin' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = ImageUploader::upload($request->file('photo'), 'members', $member->photo);
        }

        $member->update($data);
        ActivityLogger::log('member.updated', $member, ['name' => $member->name]);

        return redirect()->route('admin.members.index')
            ->with('success', "Anggota {$member->name} diperbarui.");
    }

    public function destroy(Member $member)
    {
        $name = $member->name;
        ImageUploader::delete($member->photo);
        $member->delete();
        ActivityLogger::log('member.deleted', null, ['name' => $name]);

        return redirect()->route('admin.members.index')
            ->with('success', "Anggota {$name} dihapus.");
    }
}
