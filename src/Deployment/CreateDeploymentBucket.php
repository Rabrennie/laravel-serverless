<?php

namespace LaravelServerless\Deployment;

use Aws\Exception\AwsException;
use Exception;

class CreateDeploymentBucket extends DeploymentStep
{
    public function execute() : string
    {
        $s3Client = $this->getS3Client();
        try {
            $s3Client->createBucket([
                'Bucket' => $this->getDeploymentBucketName()
            ]);
        } catch (AwsException $e) {
            throw new Exception('Failed to create s3 deployment bucket.');
        }

        return 'Successfully created s3 deployment bucket';
    }
}
