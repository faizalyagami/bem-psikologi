<?php

use App\Services\SettingService;

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return app(SettingService::class)->get($key, $default);
    }
}

if (!function_exists('menuUrl')) {
    function menuUrl($menu)
    {
        if ($menu->route_name && \Illuminate\Support\Facades\Route::has($menu->route_name)) {
            return route($menu->route_name, ['locale' => app()->getLocale()]);
        }
        return $menu->url ?? '#';
    }
}

if (!function_exists('site_content')) {
    function site_content(string $key, $default = null)
    {
        return \App\Models\SiteContent::get($key, $default);
    }
}
