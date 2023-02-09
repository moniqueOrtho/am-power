<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use App\Models\Image;
use App\Policies\PagePolicy;
use App\Policies\SitePolicy;
use App\Policies\UserPolicy;
use App\Policies\ImagePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Site::class => SitePolicy::class,
        Page::class => PagePolicy::class,
        Image::class => ImagePolicy::class
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
