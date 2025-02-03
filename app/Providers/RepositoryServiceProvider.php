<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    IUser, IDesign
};
use App\Repositories\Eloquent\{
    UserReposiroty, DesignReposiroty
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(IDesign::class, DesignReposiroty::class);
        $this->app->bind(IUser::class, UserReposiroty::class);
    }
}
