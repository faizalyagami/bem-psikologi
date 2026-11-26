<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Department extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($dept) {
            if (empty($dept->slug)) {
                $dept->slug = Str::slug($dept->name);
            }
        });
    }

    public function members()
    {
        return $this->hasMany(Member::class)->orderBy('sort_order');
    }
}
