<?php

namespace IoDigital\Repo;

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
    protected $commands = 'IoDigital\Repo\RepoScaffold';

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

        if(!file_exists(app_path('Models/Concrete/AbstractEloquentRepository.php'))){
            rename(app_path('Models/Concrete/_AbstractEloquentRepository.php')), app_path('Models/Concrete/AbstractEloquentRepository.php')));
        }

        if(!file_exists(app_path('Models/Contracts/RepositoryInterface.php'))){
            rename(app_path('Models/Contracts/_RepositoryInterface.php')), app_path('Models/Contracts/RepositoryInterface.php')));
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}