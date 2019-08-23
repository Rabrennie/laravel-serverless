<?php

namespace LaravelServerless\Generator;

class ProviderConfig
{
    public $name;
    public $region;
    public $stage;
    public $runtime = 'provided';
    public $environment = ['APP_STORAGE' => '/tmp'];
    public $iamRoleStatements = [
        [
            'Effect' => 'Allow',
            'Action' => ['something'],
            'Resource' => ['something']
        ]
    ];

    public function __construct(String $name = 'aws', String $region = 'eu-west-1', String $stage = 'dev')
    {
        $this->name = $name;
        $this->region = $region;
        $this->stage = $stage;
    }
}
