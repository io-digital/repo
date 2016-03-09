<?php

namespace garethnic\Repo;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The console commands.
     *
     * @var bool
     */
    protected $commands = 'garethnic\Repo\RepoScaffold';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Models' => app_path('Models')
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['RepoScaffold'] = $this->app->share(function($app)
        {
            return new RepoScaffold();
        });

        $this->commands($this->commands);
    }
}