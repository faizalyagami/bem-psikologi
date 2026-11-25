<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AspirationReply extends Model
{
    protected $guarded = ['id'];

    public function aspiration()
    {
        return $this->belongsTo(Aspiration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
