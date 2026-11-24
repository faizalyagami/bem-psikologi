<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Support\ActivityLogger;
use App\Support\ImageUploader;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $settings;

    public function __construct(SettingService $settings)
    {
        $this->middleware('permission:manage-settings');
        $this->settings = $settings;
    }

    public function index()
    {
        return view('admin.settings.index');
    }

    public function updateIdentity(Request $request)
    {
        $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['nullable', 'string', 'max:150'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'site_favicon' => ['nullable', 'image', 'max:512'],
        ]);

        $data = $request->only(['site_name', 'site_tagline', 'site_description']);

        if ($request->hasFile('site_logo')) {
            $old = $this->settings->get('site_logo');
            $data['site_logo'] = ImageUploader::upload($request->file('site_logo'), 'branding', $old);
        }

        if ($request->hasFile('site_favicon')) {
            $old = $this->settings->get('site_favicon');
            $data['site_favicon'] = ImageUploader::upload($request->file('site_favicon'), 'branding', $old);
        }

        $this->settings->setMany($data, 'identity');
        ActivityLogger::log('settings.identity_updated');

        return back()->with('success', 'Identitas situs berhasil diperbarui.');
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'color_primary' => ['required', 'string', 'max:7'],
            'color_secondary' => ['required', 'string', 'max:7'],
            'color_accent' => ['required', 'string', 'max:7'],
            'color_cream' => ['required', 'string', 'max:7'],
            'font_heading' => ['required', 'string', 'max:50'],
            'font_body' => ['required', 'string', 'max:50'],
        ]);

        $this->settings->setMany($request->only([
            'color_primary',
            'color_secondary',
            'color_accent',
            'color_cream',
            'font_heading',
            'font_body',
        ]), 'branding');

        ActivityLogger::log('settings.branding_updated');

        return back()->with('success', 'Branding berhasil diperbarui.');
    }

    public function updateSeo(Request $request)
    {
        $request->validate([
            'seo_meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta_description' => ['nullable', 'string', 'max:500'],
            'seo_og_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['seo_meta_title', 'seo_meta_description']);

        if ($request->hasFile('seo_og_image')) {
            $old = $this->settings->get('seo_og_image');
            $data['seo_og_image'] = ImageUploader::upload($request->file('seo_og_image'), 'branding', $old);
        }

        $this->settings->setMany($data, 'seo');
        ActivityLogger::log('settings.seo_updated');

        return back()->with('success', 'SEO berhasil diperbarui.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'numeric'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'in:tls,ssl'],
            'mail_from_name' => ['nullable', 'string', 'max:100'],
            'mail_from_address' => ['nullable', 'email'],
        ]);

        $this->settings->setMany($request->only([
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_name',
            'mail_from_address',
        ]), 'email');

        ActivityLogger::log('settings.email_updated');

        return back()->with('success', 'Konfigurasi email berhasil diperbarui.');
    }

    public function updateFooter(Request $request)
    {
        $request->validate([
            'footer_description' => ['nullable', 'string', 'max:500'],
            'footer_address' => ['nullable', 'string', 'max:255'],
            'footer_phone' => ['nullable', 'string', 'max:50'],
            'footer_email' => ['nullable', 'email'],
            'footer_instagram' => ['nullable', 'url', 'max:255'],
            'footer_facebook' => ['nullable', 'url', 'max:255'],
            'footer_twitter' => ['nullable', 'url', 'max:255'],
            'footer_youtube' => ['nullable', 'url', 'max:255'],
        ]);

        $this->settings->setMany($request->only([
            'footer_description',
            'footer_address',
            'footer_phone',
            'footer_email',
            'footer_instagram',
            'footer_facebook',
            'footer_twitter',
            'footer_youtube',
        ]), 'footer');

        ActivityLogger::log('settings.footer_updated');

        return back()->with('success', 'Footer berhasil diperbarui.');
    }
}
