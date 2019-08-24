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
        $this->functions = [
            'website' => new FunctionConfig(
                'public/index.php',
                30,
                ['arn:aws:lambda:eu-west-1:209497400698:layer:php-73-fpm:10'],
                [
                    ['http' => 'ANY /'],
                    ['http' => 'ANY /{proxy+}']
                ]
            ),
        ];

        $this->resources = new ResourcesConfig();
    }
}
