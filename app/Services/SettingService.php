<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected const CACHE_KEY = 'app_settings';
    protected const CACHE_TTL = 3600; // 1 jam

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function get(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, $value, string $group = 'general'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        $this->forget();
    }

    public function setMany(array $data, string $group): void
    {
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        $this->forget();
    }

    public function group(string $group): array
    {
        return Setting::where('group', $group)->pluck('value', 'key')->toArray();
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
