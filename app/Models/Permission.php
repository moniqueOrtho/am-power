<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\PermissionDoesNotExist;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['permission', 'description'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function findByName(string $name): Permission
    {

        $permission = self::where('permission', $name)->first();

        if (! $permission) {
            throw PermissionDoesNotExist::named($name);
        }

        return $permission;
    }

    /**
     * findById
     *
     * @param  mixed $id
     * @return Permission
     */
    public static function findById(int $id): Permission
    {
        // dd(static::class);
        $permission = self::where('id', $id)->first();

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id);
        }

        return $permission;
    }
}
