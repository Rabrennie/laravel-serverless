<?php

namespace LaravelServerless\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelServerless\Console\Commands\ServerlessDeployCommand;

class ServerlessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'../../config/serverless.php' => config_path('serverless.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/serverless.php', 'serverless');

        $this->commands([
            ServerlessDeployCommand::class
        ]);
    }
}
