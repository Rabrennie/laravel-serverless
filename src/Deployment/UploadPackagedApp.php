<?php

namespace LaravelServerless\Deployment;

use Aws\Exception\AwsException;
use Exception;

class UploadPackagedApp extends DeploymentStep
{
    public function execute() : string
    {
        $s3Client = $this->getS3Client();
        try {
            $s3Client->putObject([
                'Bucket' => $this->state->get('deploymentBucket'),
                'Key' => $this->executionTime . '/' . $this->state->get('packagedAppZipName'),
                'SourceFile' => $this->state->get('packagedAppLocation')
            ]);
        } catch (AwsException $e) {
            throw new Exception('Failed to upload packaged app to s3 bucket.');
        }

        return 'Successfully uploaded packaged app to s3 bucket.';
    }
}
