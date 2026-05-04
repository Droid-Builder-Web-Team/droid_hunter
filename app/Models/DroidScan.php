<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DroidScan
 * 
 * Represents a single droid encounter (scan) by a user or a guest visitor.
 * 
 * @property int $id
 * @property int|null $user_id The ID of the authenticated user
 * @property string|null $visitor_id The unique device/session ID for guest users
 * @property int $droid_id The ID of the droid from the Core Portal
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class DroidScan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'visitor_id',
        'droid_id',
        'event_name',
    ];
}
