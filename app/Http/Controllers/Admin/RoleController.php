<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Support\ActivityLogger;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-roles');
    }

    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            // Kelompokkan berdasarkan prefix, misal "manage-posts" => "posts"
            $parts = explode('-', $p->name);
            return $parts[1] ?? $p->name;
        });

        return view('admin.management.roles.index', compact('roles', 'permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        ActivityLogger::log('role.created', $role, ['permissions' => $request->permissions]);

        return back()->with('success', "Peran {$role->name} berhasil ditambahkan.");
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        ActivityLogger::log('role.updated', $role, ['permissions' => $request->permissions]);

        return back()->with('success', "Peran {$role->name} berhasil diperbarui.");
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Super Admin'])) {
            return back()->with('error', 'Peran Super Admin tidak dapat dihapus.');
        }

        $name = $role->name;
        $role->delete();
        ActivityLogger::log('role.deleted', null, ['name' => $name]);

        return back()->with('success', "Peran {$name} berhasil dihapus.");
    }
}
