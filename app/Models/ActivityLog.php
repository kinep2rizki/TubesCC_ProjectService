<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function ($log) {
            $log->load('user'); // Ensure user is loaded for the view
            event(new \App\Events\NewActivityLogged($log->community_id, $log));
        });
    }

    protected $fillable = [
        'user_id',
        'community_id',
        'action',
        'description',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
