<?php

namespace LaravelServerless\Generator;

use LaravelServerless\Deployment\DeploymentState;
use LaravelServerless\Generator\DeploymentBucketResourceTemplate;

class TemplateGenerator
{
    public static function generate(DeploymentState $state)
    {
        $template = [
            'AWSTemplateFormatVersion' => '2010-09-09',
            'Description' => 'Serverless AWS CloudFormation template',
            'Resources' => [
                'DeploymentBucket' => new DeploymentBucketResourceTemplate(),
                'WebsiteLogGroup' => new WebsiteLogGroupTemplate(),
                'LambdaExecutionIamRole' => new LambdaExecutionIamRoleTemplate(),
                'WebsiteLambdaFunction' => new WebsiteLambdaFunctionTemplate($state)
            ]
        ];
        
        return $template;
    }
}
