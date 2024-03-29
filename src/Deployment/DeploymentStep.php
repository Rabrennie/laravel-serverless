<?php

namespace LaravelServerless\Deployment;

use Aws\CloudFormation\CloudFormationClient;
use Aws\S3\S3Client;

abstract class DeploymentStep
{
    protected $executionTime;

    /** @var DeploymentState */
    protected $state;

    /** @var S3Client */
    protected $s3Client;

    /** @var CloudFormationClient */
    protected $cloudFormationClient;

    public function __construct(DeploymentState $state)
    {
        $this->executionTime = $state->get('executionTime');
        $this->state = $state;
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

    protected function getCloudFormationClient() : CloudFormationClient
    {
        if (is_null($this->cloudFormationClient)) {
            $this->setCloudFormationClient(new CloudFormationClient([
                'version' => '2010-05-15',
                'region' => config('serverless.aws.region'),
                'credentials' => [
                    'key' => config('serverless.aws.key'),
                    'secret' => config('serverless.aws.secret')
                ]
            ]));
        }
        return $this->cloudFormationClient;
    }

    public function setCloudFormationClient(CloudFormationClient $cloudFormationClient)
    {
        $this->cloudFormationClient = $cloudFormationClient;
    }
}
