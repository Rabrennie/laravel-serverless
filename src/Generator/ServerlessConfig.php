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
                ['${bref:layer.php-73-fpm}'],
                [
                    ['http' => 'ANY /'],
                    ['http' => 'ANY /{proxy+}']
                ]
            ),
            'artisan' => new FunctionConfig('artisan', 120, [
                '${bref:layer.php-73}',
                '${bref:layer.console}'
            ])
        ];
        $this->resources = new ResourcesConfig();
    }
}
