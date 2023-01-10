<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role', 'description', 'required'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function getPermissions()
    {
        return $this->permissions()->pluck('permission');
    }

    public function getPermissionIdsAttribute()
    {
        return $this->permissions()->pluck('id');
    }

    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            return $this->permissions->where('permission', $permission)->count();
        }

        if (is_int($permission)) {
            return $this->permissions->where('id', $permission)->count();
        }

        if ($permission instanceof Permission) {
            return $this->permissions->where('id', $permission->id)->count();
        }
    }


}
