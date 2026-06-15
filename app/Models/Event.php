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

    public function getStatusAttribute($value)
    {
        $now = now();
        $start = \Carbon\Carbon::parse($this->start_date);
        $end = \Carbon\Carbon::parse($this->end_date);

        if ($now < $start) {
            return 'Coming Soon';
        } elseif ($now > $end) {
            return 'Finished';
        } else {
            return 'Ongoing';
        }
    }
}
