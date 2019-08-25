<?php

namespace LaravelServerless\Generator;

use LaravelServerless\Generator\CloudFormationTypes;
use LaravelServerless\Generator\ResourceTemplate;

class DeploymentBucketResourceTemplate extends ResourceTemplate
{
    public function __construct()
    {
        parent::__construct(CloudFormationTypes::S3_BUCKET, [
            'BucketEncryption' => [
                'ServerSideEncryptionConfiguration' => [[
                    'ServerSideEncryptionByDefault' => [
                        'SSEAlgorithm' => 'AES256'
                    ]
                ]]
            ]
        ]);
    }
}
