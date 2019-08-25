<?php

namespace LaravelServerless\Generator;

use LaravelServerless\Generator\DeploymentBucketResourceTemplate;

class TemplateGenerator
{
    public static function generate()
    {
        $template = [
            'AWSTemplateFormatVersion' => '2010-09-09',
            'Description' => 'Serverless AWS CloudFormation template',
            'Resources' => [
                'DeploymentBucket' => new DeploymentBucketResourceTemplate(),
                'WebsiteLogGroup' => new WebsiteLogGroupTemplate()
            ]
        ];
        
        return $template;
    }
}
