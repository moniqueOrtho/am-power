<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
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
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Site $site)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Site $site)
    {
        return $user->hasPermissionTo('create_page') && $user->isOwnerOfSite($site) ?? abort(403);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Site $site)
    {
        return $user->hasPermissionTo('update_page') && $user->isOwnerOfSite($site) ?? abort(403);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Site $site)
    {
        return $user->hasPermissionTo('delete_page') && $user->isOwnerOfSite($site) ?? abort(403);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Site $site)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $Site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Site $site)
    {
        //
    }
}
