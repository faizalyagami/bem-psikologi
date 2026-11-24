<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    // Helper untuk mengambil menu berdasarkan lokasi (header/footer)
    public static function getByLocation($location = 'header')
    {
        return self::with(['children' => function ($q) {
            $q->where('is_active', true);
        }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->where('location', $location)
            ->orderBy('sort_order')
            ->get();
    }
}
