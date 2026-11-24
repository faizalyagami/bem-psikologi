<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Identitas
            ['key' => 'site_name', 'value' => 'BEM Psikologi Unisba', 'group' => 'identity'],
            ['key' => 'site_tagline', 'value' => 'Badan Eksekutif Mahasiswa Fakultas Psikologi', 'group' => 'identity'],
            ['key' => 'site_description', 'value' => 'Website resmi BEM Fakultas Psikologi Universitas Islam Bandung.', 'group' => 'identity'],
            ['key' => 'site_logo', 'value' => '', 'group' => 'identity'],
            ['key' => 'site_favicon', 'value' => '', 'group' => 'identity'],

            // Branding
            ['key' => 'color_primary', 'value' => '#123C32', 'group' => 'branding'],
            ['key' => 'color_secondary', 'value' => '#242B2A', 'group' => 'branding'],
            ['key' => 'color_accent', 'value' => '#D4AD58', 'group' => 'branding'],
            ['key' => 'color_cream', 'value' => '#F5F3ED', 'group' => 'branding'],
            ['key' => 'font_heading', 'value' => 'Plus Jakarta Sans', 'group' => 'branding'],
            ['key' => 'font_body', 'value' => 'Inter', 'group' => 'branding'],

            // SEO
            ['key' => 'seo_meta_title', 'value' => 'BEM Psikologi Unisba', 'group' => 'seo'],
            ['key' => 'seo_meta_description', 'value' => 'Website resmi BEM Fakultas Psikologi Unisba.', 'group' => 'seo'],
            ['key' => 'seo_og_image', 'value' => '', 'group' => 'seo'],

            // Email
            ['key' => 'mail_host', 'value' => 'smtp.mailtrap.io', 'group' => 'email'],
            ['key' => 'mail_port', 'value' => '2525', 'group' => 'email'],
            ['key' => 'mail_username', 'value' => '', 'group' => 'email'],
            ['key' => 'mail_password', 'value' => '', 'group' => 'email'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'group' => 'email'],
            ['key' => 'mail_from_name', 'value' => 'BEM Psikologi', 'group' => 'email'],
            ['key' => 'mail_from_address', 'value' => 'noreply@bem-psikologi.test', 'group' => 'email'],

            // Footer
            ['key' => 'footer_description', 'value' => 'Badan Eksekutif Mahasiswa Fakultas Psikologi Universitas Islam Bandung.', 'group' => 'footer'],
            ['key' => 'footer_address', 'value' => 'Jl. Tamansari No.1, Bandung, Jawa Barat', 'group' => 'footer'],
            ['key' => 'footer_phone', 'value' => '+62 812 3456 7890', 'group' => 'footer'],
            ['key' => 'footer_email', 'value' => 'bem@unisba.ac.id', 'group' => 'footer'],
            ['key' => 'footer_instagram', 'value' => 'https://instagram.com/bem.psikologi.unisba', 'group' => 'footer'],
            ['key' => 'footer_facebook', 'value' => '', 'group' => 'footer'],
            ['key' => 'footer_twitter', 'value' => '', 'group' => 'footer'],
            ['key' => 'footer_youtube', 'value' => '', 'group' => 'footer'],
        ];

        foreach ($settings as $s) {
            DB::table('settings')->updateOrInsert(
                ['key' => $s['key']],
                ['value' => $s['value'], 'group' => $s['group'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
