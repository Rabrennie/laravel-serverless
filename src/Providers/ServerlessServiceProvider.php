<?php

namespace LaravelServerless\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelServerless\Console\Commands\ServerlessDeployCommand;

class ServerlessServiceProvider extends ServiceProvider
{
    protected $commands = [
        ServerlessDeployCommand::class
    ];

    public function register()
    {
        $this->commands($this->commands);
    }
}
