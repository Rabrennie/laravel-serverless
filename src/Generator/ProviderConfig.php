<?php

namespace LaravelServerless\Generator;

class ProviderConfig
{
    public $name = 'aws';
    public $region;
    public $stage;
    public $runtime = 'provided';
    public $environment;
    public $iamRoleStatements;

    public function __construct()
    {
        $this->region = strtolower(config('serverless.aws.region'));
        $this->stage = strtolower(config('app.env'));
        $this->environment = strtolower(config('serverless.environment'));
    }
}
