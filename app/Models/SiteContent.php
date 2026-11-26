<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteContent extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('site_contents'));
        static::deleted(fn() => Cache::forget('site_contents'));
    }

    public static function get(string $key, $default = null)
    {
        $contents = Cache::rememberForever('site_contents', function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $contents[$key] ?? $default;
    }
}
