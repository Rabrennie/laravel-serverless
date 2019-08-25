<?php

namespace LaravelServerless\Generator;

use LaravelServerless\Deployment\DeploymentState;
use LaravelServerless\Generator\CloudFormationTypes;
use LaravelServerless\Generator\ResourceTemplate;

class WebsiteLambdaFunctionTemplate extends ResourceTemplate
{
    public function __construct(DeploymentState $state)
    {
        $lambdaName = strtolower(config('app.name') . '-' . config('app.env') . '-website');
        $region = config('serverless.aws.region');
        parent::__construct(
            CloudFormationTypes::LAMBDA_FUNCTION,
            [
                'Code' => [
                    'S3Bucket' => ['Ref' => 'DeploymentBucket'],
                    'S3Key' => $state->get('executionTime') . "/" . $state->get('packagedAppZipName')
                ],
                'FunctionName' => $lambdaName,
                'Handler' => 'public/index.php',
                'MemorySize' => 1024,
                'Role' => [
                    'Fn::GetAtt' => [
                        'LambdaExecutionIamRole',
                        'Arn'
                    ]
                ],
                'Runtime' => 'provided',
                'Timeout' => 30,
                'Layers' => ["arn:aws:lambda:{$region}:209497400698:layer:php-73-fpm:10"]
            ],
            [
                'WebsiteLogGroup',
                'LambdaExecutionIamRole'
            ]
        );
    }
}
