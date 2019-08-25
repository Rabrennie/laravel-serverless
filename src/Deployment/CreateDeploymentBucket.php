<?php

namespace LaravelServerless\Deployment;

use LaravelServerless\Generator\DeploymentBucketResourceTemplate;

class CreateDeploymentBucket extends CloudformationDeploymentStep
{
    public function execute(): string
    {
        $template = [
            'AWSTemplateFormatVersion' => '2010-09-09',
            'Description' => 'Serverless AWS CloudFormation template',
            'Resources' => [
                'DeploymentBucket' => new DeploymentBucketResourceTemplate(),
            ]
        ];

        $stackName = $this->getStackName();

        $this->validateTemplate($template);
        $this->createOrUpdateStack($stackName, $template);

        $status = $this->getStackStatus($stackName);
        while ($status !== 'CREATE_COMPLETE' && $status !== 'UPDATE_COMPLETE') {
            sleep(5);
            $status = $this->getStackStatus($stackName);
        };

        $result = $this->getCloudFormationClient()->describeStackResource([
            'StackName' => $stackName,
            'LogicalResourceId' => 'DeploymentBucket'
        ]);

        $deploymentBucket = $result->get('StackResourceDetail')['PhysicalResourceId'];
        $this->state->set('deploymentBucket', $deploymentBucket);

        return 'success';
    }
}
