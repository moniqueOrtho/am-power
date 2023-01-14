<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Exceptions\PermissionDoesNotExist;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'name',
        'email',
        'password',
        'role_id',
        'required'
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

    // De site where the user belongs to
    public function sites()
    {
        return $this->belongsToMany(Site::class)->withTimestamps();
    }

    public function ownedSites()
    {
        return $this->sites()->where('owner_id', $this->id);
    }

    public function isOwnerOfSite($site)
    {
        if ($site instanceof Site) {
            $site = $site->id;
        }
        return (bool)$this->sites()
                ->where('site_id', $site)
                ->where('owner_id', $this->id)
                ->count();
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
            return $this->role()->where('role', $role)->count();
        }

        if (is_int($role)) {
            return $this->role()->where('id', $role)->count();
        }

        if ($role instanceof Role) {
            return $this->role()->where('id', $role->id)->count();
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

    public function hasPermissionTo($permission): bool
    {
        $permissionClass = new Permission;

        if (is_string($permission)) {

            $permission = $permissionClass->findByName(
                $permission
            );
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById(
                $permission
            );
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist;
        }

        return $this->hasPermissionViaRole($permission, $this->role_id);
    }

    /**
     * Determine if the model has, via role, the given permission.
     *
     * @param Permission $permission
     *
     * @return bool
     */
    protected function hasPermissionViaRole(Permission $permission, $roleId): bool
    {
        return $permission->roles()->where('id', $roleId)->count();
    }

    /**
     * Get the images for a user
     *
     */
    Public function images() {
        return $this->hasMany(Image::class);
    }
}
