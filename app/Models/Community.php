<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];



    public function members()
    {
        return $this->hasMany(CommunityMember::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
