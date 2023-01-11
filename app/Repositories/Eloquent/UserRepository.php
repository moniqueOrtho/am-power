<?php

namespace App\Repositories\Eloquent;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\BaseRepository;

class UserRepository extends BaseRepository implements IUser
{
    public function model()
    {
        return User::class;
    }

    public function findMembers($role, $siteId)
    {
        if(auth()->user()->hasRole('superadmin')) {
            return $this->findWhere('role_id', $role->id);
        } else {
            return $this->model
                ->whereHas('sites', function($query) use ($siteId) {
                    $query->where('site_id', $siteId);
                })
                ->where('role_id', $role->id)
                ->get();
        }
    }
}
