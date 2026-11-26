<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            // HERO
            ['key' => 'hero_title', 'label' => 'Judul Hero', 'type' => 'text', 'value' => 'Bersama Berkarya, Bersama Berdampak', 'group' => 'hero'],
            ['key' => 'hero_subtitle', 'label' => 'Subtitle Hero', 'type' => 'textarea', 'value' => 'Website resmi BEM Fakultas Psikologi Universitas Islam Bandung. Wadah aspirasi, kreasi, dan kontribusi mahasiswa psikologi.', 'group' => 'hero'],
            ['key' => 'hero_image', 'label' => 'Gambar Hero', 'type' => 'image', 'value' => '', 'group' => 'hero'],

            // SAMBUTAN KETUA
            ['key' => 'welcome_title', 'label' => 'Judul Sambutan', 'type' => 'text', 'value' => 'Sinergi untuk Psikologi yang Berdampak', 'group' => 'welcome'],
            ['key' => 'welcome_message', 'label' => 'Pesan Sambutan', 'type' => 'textarea', 'value' => 'Selamat datang di website resmi BEM Fakultas Psikologi Unisba. Kami berkomitmen menjadi jembatan aspirasi mahasiswa dan mitra kampus dalam membangun generasi psikologi yang unggul, berintegritas, dan berdampak bagi masyarakat.', 'group' => 'welcome'],
            ['key' => 'welcome_name', 'label' => 'Nama Ketua', 'type' => 'text', 'value' => 'Nama Ketua BEM', 'group' => 'welcome'],
            ['key' => 'welcome_photo', 'label' => 'Foto Ketua', 'type' => 'image', 'value' => '', 'group' => 'welcome'],

            // CTA
            ['key' => 'cta_title', 'label' => 'Judul CTA', 'type' => 'text', 'value' => 'Punya Aspirasi?', 'group' => 'cta'],
            ['key' => 'cta_description', 'label' => 'Deskripsi CTA', 'type' => 'textarea', 'value' => 'Suarakan pendapat, saran, atau keluhan Anda untuk kemajuan Fakultas Psikologi. Kami siap mendengarkan.', 'group' => 'cta'],
            ['key' => 'cta_button_text', 'label' => 'Teks Tombol CTA', 'type' => 'text', 'value' => 'Kirim Aspirasi', 'group' => 'cta'],
        ];

        foreach ($contents as $c) {
            DB::table('site_contents')->updateOrInsert(
                ['key' => $c['key']],
                array_merge($c, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
