<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // Departemen
        $departments = [
            ['name' => 'Psikologi Sosial', 'icon' => 'users', 'sort_order' => 1],
            ['name' => 'Psikologi Pendidikan', 'icon' => 'book-open', 'sort_order' => 2],
            ['name' => 'Psikologi Klinis', 'icon' => 'heart-pulse', 'sort_order' => 3],
            ['name' => 'Psikologi Industri', 'icon' => 'briefcase', 'sort_order' => 4],
            ['name' => 'Media & Informasi', 'icon' => 'megaphone', 'sort_order' => 5],
        ];
        foreach ($departments as $d) {
            Department::firstOrCreate(['slug' => \Str::slug($d['name'])], $d);
        }

        // Jabatan
        $positions = [
            ['name' => 'Ketua', 'level' => 'pimpinan', 'sort_order' => 1],
            ['name' => 'Wakil Ketua', 'level' => 'pimpinan', 'sort_order' => 2],
            ['name' => 'Sekretaris', 'level' => 'pengurus_inti', 'sort_order' => 3],
            ['name' => 'Bendahara', 'level' => 'pengurus_inti', 'sort_order' => 4],
            ['name' => 'Kepala Departemen', 'level' => 'kepala_departemen', 'sort_order' => 5],
            ['name' => 'Staf', 'level' => 'staf', 'sort_order' => 6],
        ];
        foreach ($positions as $p) {
            Position::firstOrCreate(['slug' => \Str::slug($p['name'])], $p);
        }
    }
}
