<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        $role = '';
        if($user->hasRole('superadmin')) {
            $role = 'update_admin';
        }
        if($user->hasRole('admin')){
            $role = 'update_subscriber';
        }

        return ($user->hasPermissionTo($role, $user['role_id']));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $role = '';
        if($user->hasRole('superadmin')) {
            $role = 'create_admin';
        }
        if($user->hasRole('admin')){
            $role = 'create_subscriber';
        }

        return ($user->hasPermissionTo($role, $user['role_id']));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        $role = '';
        if($user->hasRole('superadmin')) {
            $role = 'update_admin';
        }
        if($user->hasRole('admin')){
            $role = 'update_subscriber';
        }

        return ($user->hasPermissionTo($role, $user['role_id']));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        $role = '';
        if($user->hasRole('superadmin')) {
            $role = 'delete_admin';
        }
        if($user->hasRole('admin')){
            $role = 'delete_subscriber';
        }

        return ($user->hasPermissionTo($role, $user['role_id']));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
