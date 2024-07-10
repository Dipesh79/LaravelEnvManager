<?php

namespace Dipesh79\LaravelEnvManager;


use Dipesh79\LaravelEnvManager\Console\Commands\BackupEnvCommand;
use Dipesh79\LaravelEnvManager\Console\Commands\ListEnvCommand;
use Dipesh79\LaravelEnvManager\Console\Commands\RemoveEnvCommand;
use Dipesh79\LaravelEnvManager\Console\Commands\RestoreEnvCommand;
use Dipesh79\LaravelEnvManager\Console\Commands\SetEnvCommand;
use Illuminate\Support\ServiceProvider;

class LaravelEnvManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->commands([
            SetEnvCommand::class,
            RemoveEnvCommand::class,
            ListEnvCommand::class,
            BackupEnvCommand::class,
            RestoreEnvCommand::class
        ]);


    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'envManager');
        $this->publishes([
            __DIR__ . '/config/envManager.php' => config_path('envManager.php'),
        ]);
    }
}
