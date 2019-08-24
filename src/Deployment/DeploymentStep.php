<?php

namespace LaravelServerless\Deployment;

abstract class DeploymentStep
{
    protected $executionTime;

    public function __construct($executionTime)
    {
        $this->executionTime = $executionTime;
    }

    abstract public function execute() : string;

    protected function getS3Client()
    {
        return new S3Client([
            'version' => '2006-03-01',
            'region' => config('serverless.aws.region'),
            'credentials' => [
                'key' => config('serverless.aws.key'),
                'secret' => config('serverless.aws.secret')
            ]
        ]);
    }

    protected function getDeploymentBucketName()
    {
        $appName = config('app.name');
        $appEnv = config('app.env');
        $timestamp = time();

        return strtolower(str_replace(' ', '-', "{$appName}-{$appEnv}-deployment-{$this->executionTime}"));
    }
}
