<?php

namespace Quanticheart\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Quanticheart\Laravel\Commands\MakeControllerArch;
use Quanticheart\Laravel\Commands\MakeHelperCommand;
use Quanticheart\Laravel\Commands\MakeRepositoryArch;
use Quanticheart\Laravel\Commands\MakeServiceArch;
use Quanticheart\Laravel\Commands\MigrateQuanticHeartCommand;
use Quanticheart\Laravel\Middlewares\ApiTokenMiddleware;

class QuanticHeartProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /** \Illuminate\Routing\Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('api-token', ApiTokenMiddleware::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__ . "/../");
//        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom( __DIR__ . '../migrations');
//        $this->loadViewsFrom(__DIR__.'/views', 'todolist');
//        $this->publishes([
//            __DIR__.'/views' => base_path('resources/views/quanticheart/todolist'),
//        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeHelperCommand::class,
                MigrateQuanticHeartCommand::class,
                MakeRepositoryArch::class,
                MakeServiceArch::class,
                MakeControllerArch::class,
            ]);
        }
    }
}
