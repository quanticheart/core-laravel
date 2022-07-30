<?php

namespace Quanticheart\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Quanticheart\Laravel\Commands\MakeHelperCommand;
use Quanticheart\Laravel\Commands\MigrateQuanticHeartCommand;

class QuanticHeartProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->loadRoutesFrom(__DIR__.'/routes.php');
//        $this->loadMigrationsFrom(__DIR__.'/migrations');
//        $this->loadViewsFrom(__DIR__.'/views', 'todolist');
//        $this->publishes([
//            __DIR__.'/views' => base_path('resources/views/quanticheart/todolist'),
//        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeHelperCommand::class,
                MigrateQuanticHeartCommand::class
            ]);
        }
    }
}
