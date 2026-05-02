<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DroidScan extends Model
{
    protected $fillable = [
        'user_id',
        'visitor_id',
        'droid_id',
    ];
}
