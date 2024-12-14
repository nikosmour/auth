<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $paths = glob(database_path('migrations/*'), GLOB_ONLYDIR);

        $this->loadMigrationsFrom(array_merge([database_path('migrations')], $paths));
    }
}
