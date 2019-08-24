<?php

namespace LaravelServerless\Deployment;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Exception;

class CreateDeploymentBucket implements iDeploymentStep
{
    /** @var S3Client */
    protected $s3Client;

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => '2006-03-01',
            'region' => config('serverless.aws.region'),
            'credentials' => [
                'key' => config('serverless.aws.key'),
                'secret' => config('serverless.aws.secret')
            ]
        ]);
    }

    public function execute() : string
    {
        $bucketname = $this->getBucketName();

        try {
            $this->s3Client->createBucket([
                'Bucket' => $bucketname
            ]);
        } catch (AwsException $e) {
            throw new Exception('Failed to create s3 deployment bucket.');
        }

        return 'Successfully created s3 deployment bucket';
    }

    protected function getBucketName()
    {
        $appName = config('app.name');
        $appEnv = config('app.env');
        $timestamp = time();

        return strtolower(str_replace(' ', '-', "{$appName}-{$appEnv}-deployment-{$timestamp}"));
    }
}
