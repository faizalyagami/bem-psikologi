<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Member extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['photo_url'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=123C32&color=D6B66D&size=256';
    }
}
