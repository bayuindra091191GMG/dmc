<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'admin.sections.header',
            'App\Http\ViewComposers\NotificationComposer'
        );
        view()->composer(
            'admin.sections.navigation',
            'App\Http\ViewComposers\NavigationComposer'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
