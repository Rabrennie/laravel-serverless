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
        $this->region = config('serverless.aws.region');
        $this->stage = config('app.env');
        $this->environment = config('serverless.environment');
    }
}
