<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityMessage extends Model
{
    protected $fillable = [
        'community_id',
        'user_id',
        'content'
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
