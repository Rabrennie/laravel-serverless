<?php

namespace LaravelServerless\Deployment;

use Aws\CloudFormation\CloudFormationClient;
use Aws\CloudFormation\Exception\CloudFormationException;
use \Aws\Result;

use Exception;
use LaravelServerless\Generator\TemplateGenerator;

abstract class CloudformationDeploymentStep extends DeploymentStep
{
    abstract public function execute() : string;

    public function getStackName()
    {
        return str_replace(' ', '-', strtolower(config('app.name') . '-' . config('app.env')));
    }

    public function stackExists($stackName)
    {
        try {
            $this->getCloudFormationClient()->describeStacks([
                'StackName' => $stackName
            ]);
        } catch (CloudFormationException $e) {
            return false;
        }

        return true;
    }

    public function validateTemplate($template)
    {
        try {
            $this->getCloudFormationClient()->validateTemplate([
                'TemplateBody' => json_encode($template, JSON_UNESCAPED_SLASHES)
            ]);
        } catch (CloudFormationException $e) {
            throw new Exception($e->getAwsErrorMessage());
        }
    }

    public function createOrUpdateStack($stackName, $template)
    {
        try {
            if ($this->stackExists($stackName)) {
                $this->getCloudFormationClient()->updateStack([
                    'Capabilities' => ['CAPABILITY_NAMED_IAM'],
                    'StackName' => $stackName,
                    'TemplateBody' => json_encode($template, JSON_UNESCAPED_SLASHES)
                ]);
            } else {
                $this->getCloudFormationClient()->createStack([
                    'Capabilities' => ['CAPABILITY_NAMED_IAM'],
                    'StackName' => $stackName,
                    'TemplateBody' => json_encode($template, JSON_UNESCAPED_SLASHES)
                ]);
            }
        } catch (CloudFormationException $e) {
            $message = $e->getAwsErrorMessage();
            if ($message !== 'No updates are to be performed.') {
                throw new Exception('Could not create cloudformation stack: ' . $message);
            }
        }
        
        return true;
    }

    public function getStackStatus($stackName)
    {
        /** @var Result */
        $result = $this->getCloudFormationClient()->describeStacks([
            'StackName' => $stackName
        ]);

        return $result->get('Stacks')[0]['StackStatus'];
    }
}
