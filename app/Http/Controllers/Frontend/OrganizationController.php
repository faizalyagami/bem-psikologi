<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Member;
use App\Models\Position;

class OrganizationController extends Controller
{
    public function index()
    {
        $pimpinan = Member::with(['department', 'position'])
            ->whereHas('position', fn($q) => $q->whereIn('level', ['pimpinan', 'pengurus_inti']))
            ->orderBy('sort_order')
            ->get();

        $departments = Department::with(['members.position'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.organization.index', compact('pimpinan', 'departments'));
    }
}
