<?php
namespace LaravelServerless\Generator;

class ServerlessConfig
{
    public $service;
    public $provider;
    public $plugins;
    public $custom;
    public $package;
    public $functions;
    public $resources;

    public function __construct()
    {
        $this->service = config('app.name');
        $this->provider = new ProviderConfig();
        $this->package = new PackageConfig();
    }
}
