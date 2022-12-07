<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gender',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
    ];

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
    ];

    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }

    // Relationship with Role table
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Determine if the model has the given role.
     *
     * @param string|int|array|\Illuminate\Support\Collection $role
     * @param string|null $guard
     * @return bool
     */
    public function hasRole($role, string $guard = null): bool
    {
        if (is_string($role)) {
            return $this->role->where('role', $role)->count();
        }

        if (is_int($role)) {
            return $this->role->where('id', $role)->count();
        }

        if ($role instanceof Role) {
            return $this->role->where('id', $role->id)->count();
        }
    }

    public function getRoleNameAttribute()
    {
        return $this->role()->value('role');
    }

    // Helper function for permissions through role
    public function getPermissionsAttribute()
    {
        $role = Role::find($this->role_id);
        return $role->permissions()->pluck('permission');
    }


}
