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

    public function __construct(String $name, ProviderConfig $provider)
    {
        $this->service = $name;
        $this->provider = $provider;
    }
}
