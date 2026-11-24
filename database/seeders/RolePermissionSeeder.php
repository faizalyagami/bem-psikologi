<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',

            'media.view',
            'media.create',
            'media.update',
            'media.delete',

            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',

            'translations.view',
            'translations.create',
            'translations.update',
            'translations.delete',

            'audit-logs.view',

            'posts.view',
            'posts.create',
            'posts.update',
            'posts.delete',
            'posts.publish',

            'pages.view',
            'pages.create',
            'pages.update',
            'pages.delete',
            'pages.publish',

            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            'tags.view',
            'tags.create',
            'tags.update',
            'tags.delete',

            'events.view',
            'events.create',
            'events.update',
            'events.delete',

            'settings.view',
            'settings.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Super Admin
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions(
            Permission::all()
        );

        // Admin BEM
        Role::firstOrCreate([
            'name' => 'admin-bem',
            'guard_name' => 'web',
        ]);

        // Editor
        Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        // Author
        Role::firstOrCreate([
            'name' => 'author',
            'guard_name' => 'web',
        ]);

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}
