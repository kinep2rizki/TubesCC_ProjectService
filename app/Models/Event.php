<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'community_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'status',
        'capacity',
        'is_qr_active',
        'certificate_automation_active',
        'certificate_template',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
