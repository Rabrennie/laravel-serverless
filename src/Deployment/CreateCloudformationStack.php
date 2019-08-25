<?php

namespace LaravelServerless\Deployment;

use Aws\CloudFormation\CloudFormationClient;
use Aws\CloudFormation\Exception\CloudFormationException;
use \Aws\Result;

use Exception;
use LaravelServerless\Generator\TemplateGenerator;

class CreateCloudformationStack extends DeploymentStep
{
    public function execute() : string
    {
        $template = TemplateGenerator::generate();

        $stackName = 'testing-stack';
        
        $this->validateTemplate($template);
        $this->createStack($stackName, $template);
        
        $status = $this->getStackStatus($stackName);
        while ($status !== 'CREATE_COMPLETE' && $status !== 'UPDATE_COMPLETE') {
            sleep(5);
            $status = $this->getStackStatus($stackName);
        };

        $result = $this->getCloudFormationClient()->describeStackResource([
            'StackName' => 'testing-stack',
            'LogicalResourceId' => 'DeploymentBucket'
        ]);
        
        $deploymentBucket = $result->get('StackResourceDetail')['PhysicalResourceId'];
        $this->state->set('deploymentBucket', $deploymentBucket);

        return 'success';
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
                'TemplateBody' => json_encode($template)
            ]);
        } catch (CloudFormationException $e) {
            throw new Exception($e->getAwsErrorMessage());
        }
    }

    public function createStack($stackName, $template)
    {
        try {
            if ($this->stackExists($stackName)) {
                $this->getCloudFormationClient()->updateStack([
                    'StackName' => $stackName,
                    'TemplateBody' => json_encode($template)
                ]);
            } else {
                $this->getCloudFormationClient()->createStack([
                    'StackName' => $stackName,
                    'TemplateBody' => json_encode($template)
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
