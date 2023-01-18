<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    IPage,
    IPost,
    ISection,
    ISite,
    ISubpage,
    IUser,
    IRole,
    IPermission
};

use App\Repositories\Eloquent\{
    PageRepository,
    PostRepository,
    SectionRepository,
    SiteRepository,
    SubpageRepository,
    UserRepository,
    RoleRepository,
    PermissionRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IPage::class, PageRepository::class);
        $this->app->bind(IPost::class, PostRepository::class);
        $this->app->bind(ISection::class, SectionRepository::class);
        $this->app->bind(ISite::class, SiteRepository::class);
        $this->app->bind(ISubpage::class, SubpageRepository::class);
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IRole::class, RoleRepository::class);
        $this->app->bind(IPermission::class, PermissionRepository::class);
    }
}
