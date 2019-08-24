<?php

namespace LaravelServerless\Deployment;

use Aws\Exception\AwsException;
use Exception;

class UploadPackagedApp extends DeploymentStep
{
    public function execute() : string
    {
        $s3Client = $this->getS3Client();
        echo storage_path('laravel-serverless/') . strtolower(config('app.name') . '.zip');
        try {
            $s3Client->putObject([
                'Bucket' => $this->getDeploymentBucketName(),
                'Key' => strtolower(config('app.name')) . '.zip',
                'SourceFile' => realpath(storage_path('laravel-serverless/') . strtolower(config('app.name') . '.zip'))
            ]);
        } catch (AwsException $e) {
            throw new Exception('Failed to upload packaged app to s3 bucket.');
        }

        return 'Successfully uploaded packaged app to s3 bucket.';
    }
}
