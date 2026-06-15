<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url'
    ];

    /**
     * Get the communities this user owns.
     */
    public function ownedCommunities()
    {
        return $this->hasMany(Community::class, 'owner_id');
    }

    /**
     * Get the communities this user is a member of.
     */
    public function communityMemberships()
    {
        return $this->hasMany(CommunityMember::class);
    }

    /**
     * Get the events this user is participating in.
     */
    public function participations()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * Check if user has a specific role in a community.
     */
    public function hasCommunityRole($communityId, $roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        return $this->communityMemberships()
                    ->where('community_id', $communityId)
                    ->whereIn('role', $roles)
                    ->exists();
    }

    /**
     * Check if user can manage events in a community.
     */
    public function canManageEvent($communityId)
    {
        if ($this->hasRole('Super Admin') || $this->hasPermissionTo('manage events')) {
            return true;
        }
        return $this->hasCommunityRole($communityId, ['Owner', 'Admin']);
    }

    /**
     * Check if user can manage participants in a community.
     */
    public function canManageParticipants($communityId)
    {
        if ($this->hasRole('Super Admin') || $this->hasPermissionTo('manage participants')) {
            return true;
        }
        return $this->hasCommunityRole($communityId, ['Owner', 'Admin', 'Moderator']);
    }

    /**
     * Check if user can manage attendance in a community.
     */
    public function canManageAttendance($communityId)
    {
        if ($this->hasRole('Super Admin') || $this->hasPermissionTo('manage attendance')) {
            return true;
        }
        return $this->hasCommunityRole($communityId, ['Owner', 'Admin', 'Moderator']);
    }

    /**
     * Check if user can manage certificates in a community.
     */
    public function canManageCertificates($communityId)
    {
        if ($this->hasRole('Super Admin') || $this->hasPermissionTo('manage certificates')) {
            return true;
        }
        return $this->hasCommunityRole($communityId, ['Owner', 'Admin']);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}