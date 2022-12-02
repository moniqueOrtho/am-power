<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Contracts\IRole;
use App\Repositories\Eloquent\BaseRepository;

class RoleRepository extends BaseRepository implements IRole
{
    public function model()
    {
        return Role::class;
    }
}
