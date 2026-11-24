<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SettingSeeder::class);

        // 1. Buat Permissions
        $permissions = [
            'view-dashboard',
            'manage-users',
            'manage-roles',
            'manage-menus',
            'manage-translations',
            'view-logs',
            'manage-posts',
            'manage-pages',
            'manage-categories',
            'manage-tags',
            'manage-events',
            'manage-media',
            'manage-settings',
            'manage-aspirations',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 2. Buat Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        $adminBem = Role::firstOrCreate(['name' => 'Admin BEM']);
        $adminBem->syncPermissions([
            'view-dashboard',
            'manage-posts',
            'manage-pages',
            'manage-categories',
            'manage-tags',
            'manage-events',
            'manage-media',
            'manage-aspirations',
            'manage-settings'
        ]);

        $sekretaris = Role::firstOrCreate(['name' => 'Sekretaris']);
        $sekretaris->syncPermissions(['view-dashboard', 'manage-posts', 'manage-pages', 'manage-events']);

        $humas = Role::firstOrCreate(['name' => 'Humas']);
        $humas->syncPermissions(['view-dashboard', 'manage-posts', 'manage-media']);

        Role::firstOrCreate(['name' => 'Viewer'])->syncPermissions(['view-dashboard']);

        // 3. Buat Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@bem-psikologi.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('Super Admin');

        // 4. Buat Kategori Contoh
        \App\Models\Category::firstOrCreate(['slug' => 'berita'], ['name' => 'Berita']);
        \App\Models\Category::firstOrCreate(['slug' => 'artikel'], ['name' => 'Artikel']);
        \App\Models\Category::firstOrCreate(['slug' => 'pengumuman'], ['name' => 'Pengumuman']);

        // 5. Buat Menu Bawaan
        $menus = [
            ['title' => 'Beranda', 'route_name' => 'home', 'sort_order' => 1, 'location' => 'header', 'is_active' => true],
            ['title' => 'Tentang BEM', 'route_name' => null, 'sort_order' => 2, 'location' => 'header', 'is_active' => true],
            ['title' => 'Informasi', 'route_name' => null, 'sort_order' => 3, 'location' => 'header', 'is_active' => true],
            ['title' => 'Galeri', 'route_name' => 'frontend.gallery', 'sort_order' => 4, 'location' => 'header', 'is_active' => true],
            ['title' => 'Kontak', 'route_name' => 'frontend.contact', 'sort_order' => 5, 'location' => 'header', 'is_active' => true],
        ];
        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}
