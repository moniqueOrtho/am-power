<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Repositories\Contracts\IPermission;
use App\Repositories\Eloquent\BaseRepository;

class PermissionRepository extends BaseRepository implements IPermission
{
    public function model()
    {
        return Permission::class;
    }
}
