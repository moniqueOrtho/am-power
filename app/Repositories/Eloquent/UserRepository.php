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

    public function findMembers($role)
    {
        if(auth()->user()->hasRole('superadmin')) {
            return $this->findWhere('role_id', $role->id);
        } else {
            return $this->model
                ->has('sites')
                ->where('owner_id', auth()->user()->id)
                ->where('role_id', $role->id)
                ->get();
        }
    }
}
