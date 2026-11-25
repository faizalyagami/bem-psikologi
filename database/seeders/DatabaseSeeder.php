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

        // 1. Permissions
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
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 2. Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $adminBem = Role::firstOrCreate(['name' => 'Admin BEM', 'guard_name' => 'web']);
        $adminBem->syncPermissions([
            'view-dashboard',
            'manage-posts',
            'manage-pages',
            'manage-categories',
            'manage-tags',
            'manage-events',
            'manage-media',
            'manage-aspirations',
            'manage-settings',
        ]);

        $sekretaris = Role::firstOrCreate(['name' => 'Sekretaris', 'guard_name' => 'web']);
        $sekretaris->syncPermissions(['view-dashboard', 'manage-posts', 'manage-pages', 'manage-events']);

        $humas = Role::firstOrCreate(['name' => 'Humas', 'guard_name' => 'web']);
        $humas->syncPermissions(['view-dashboard', 'manage-posts', 'manage-media']);

        Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web'])
            ->syncPermissions(['view-dashboard']);

        // 3. Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@bem-psikologi.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $user->syncRoles(['Super Admin']);  // pakai syncRoles agar bersih

        // 4. Kategori
        \App\Models\Category::firstOrCreate(['slug' => 'berita'], ['name' => 'Berita']);
        \App\Models\Category::firstOrCreate(['slug' => 'artikel'], ['name' => 'Artikel']);
        \App\Models\Category::firstOrCreate(['slug' => 'pengumuman'], ['name' => 'Pengumuman']);

        // 5. Menu — pakai firstOrCreate agar idempoten
        $menus = [
            ['title' => 'Beranda', 'route_name' => 'home', 'sort_order' => 1, 'location' => 'header', 'is_active' => true],
            ['title' => 'Tentang BEM', 'route_name' => null, 'sort_order' => 2, 'location' => 'header', 'is_active' => true],
            ['title' => 'Informasi', 'route_name' => null, 'sort_order' => 3, 'location' => 'header', 'is_active' => true],
            ['title' => 'Galeri', 'route_name' => 'frontend.gallery', 'sort_order' => 4, 'location' => 'header', 'is_active' => true],
            ['title' => 'Kontak', 'route_name' => 'frontend.contact', 'sort_order' => 5, 'location' => 'header', 'is_active' => true],
        ];
        foreach ($menus as $menu) {
            \App\Models\Menu::firstOrCreate(['title' => $menu['title']], $menu);
        }

        // 6. Halaman Tentang BEM
        $aboutPage = \App\Models\Page::firstOrCreate(
            ['status' => 'published'],
            ['user_id' => $user->id, 'status' => 'published']
        );

        $aboutPage->translations()->updateOrCreate(
            ['locale' => 'id'],
            [
                'title' => 'Tentang BEM',
                'slug' => 'tentang-bem',
                'content' => '<h2>Visi</h2><p>Menjadi lembaga eksekutif mahasiswa yang ...</p><h2>Misi</h2><ul><li>Misi 1</li><li>Misi 2</li></ul>',
            ]
        );
    }
}
