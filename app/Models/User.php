<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'failed_try',
    ];
    protected $appends = ['status', 'permission'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getStatusAttribute()
    {
        $data[0] = [
            'id' => 0,
            'text' => 'Suspend',
            'className' => 'bg-warning text-dark',
        ];
        $data[1] = [
            'id' => 1,
            'text' => 'Active',
            'className' => 'bg-success',
        ];
        $data[-1] = [
            'id' => -1,
            'text' => 'Rejected',
            'className' => 'bg-danger',
        ];
        $data[-2] = [
            'id' => -2,
            'text' => 'Deleted',
            'className' => 'bg-danger',
        ];
        return $data[$this->is_active];
    }

    public function hasEntities()
    {
        return $this->hasManyThrough(
            SsoEntity::class,
            SsoUserHasEntity::class,
            'user_id', // Foreign key pada tabel perantara (SsoUserHasEntity)
            'id', // Foreign key pada model User
            'id', // Local key pada model User
            'entity_id' // Local key pada tabel perantara (SsoUserHasEntity)
        );
    }

    public function hasModule()
    {
        return $this->hasManyThrough(
            SsoModule::class,
            SsoUserHasModule::class,
            'user_id', // Foreign key pada tabel perantara (SsoUserHasModule)
            'id', // Foreign key pada model User
            'id', // Local key pada model User
            'module_id' // Local key pada tabel perantara (SsoUserHasModule)
        );
    }

    public function getPermissionAttribute()
    {
        return $this->getAllPermissions();
    }
}
