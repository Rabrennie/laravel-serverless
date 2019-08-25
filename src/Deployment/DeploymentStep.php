<?php

namespace LaravelServerless\Deployment;

use Aws\S3\S3Client;

abstract class DeploymentStep
{
    protected $executionTime;

    /** @var S3Client */
    protected $s3Client;

    public function __construct($executionTime)
    {
        $this->executionTime = $executionTime;
    }

    abstract public function execute() : string;

    protected function getS3Client() : S3Client
    {
        if (is_null($this->s3Client)) {
            $this->setS3Client(new S3Client([
                'version' => '2006-03-01',
                'region' => config('serverless.aws.region'),
                'credentials' => [
                    'key' => config('serverless.aws.key'),
                    'secret' => config('serverless.aws.secret')
                ]
            ]));
        }
        return $this->s3Client;
    }

    public function setS3Client(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    protected function getDeploymentBucketName() : string
    {
        $appName = config('app.name');
        $appEnv = config('app.env');

        return strtolower(str_replace(' ', '-', "{$appName}-{$appEnv}-deployment-{$this->executionTime}"));
    }
}
