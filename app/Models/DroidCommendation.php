<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DroidCommendation extends Model
{
    protected $fillable = [
        'droid_id',
        'user_id',
        'visitor_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
