<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Position extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($pos) {
            if (empty($pos->slug)) {
                $pos->slug = Str::slug($pos->name);
            }
        });
    }

    public function members()
    {
        return $this->hasMany(Member::class)->orderBy('sort_order');
    }
}
