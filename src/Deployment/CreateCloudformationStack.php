<?php

namespace LaravelServerless\Deployment;

use Aws\CloudFormation\Exception\CloudFormationException;
use \Aws\Result;

use Exception;
use LaravelServerless\Generator\TemplateGenerator;

class CreateCloudformationStack extends CloudformationDeploymentStep
{
    public function execute() : string
    {
        $template = TemplateGenerator::generate($this->state);

        $stackName = $this->getStackName();
        
        $this->validateTemplate($template);
        $this->createOrUpdateStack($stackName, $template);
        
        $status = $this->getStackStatus($stackName);
        while ($status !== 'CREATE_COMPLETE' && $status !== 'UPDATE_COMPLETE') {
            sleep(5);
            $status = $this->getStackStatus($stackName);
        };

        return 'success';
    }
}
