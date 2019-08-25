<?php

namespace LaravelServerless\Deployment;

use Aws\CloudFormation\CloudFormationClient;
use Aws\CloudFormation\Exception\CloudFormationException;
use \Aws\Result;

use Exception;
use LaravelServerless\Generator\TemplateGenerator;

class CreateCloudformationStack extends DeploymentStep
{
    /** @var CloudFormationClient */
    private $cloudformationClient;

    public function execute() : string
    {
        $template = TemplateGenerator::generate();

        $this->cloudformationClient = new CloudFormationClient([
            'version' => '2010-05-15',
            'region' => config('serverless.aws.region'),
            'credentials' => [
                'key' => config('serverless.aws.key'),
                'secret' => config('serverless.aws.secret')
            ]
        ]);

        $stackName = 'testing-stack';
        
        $this->validateTemplate($template);
        $this->createStack($stackName, $template);
        
        $status = $this->getStackStatus($stackName);
        while ($status !== 'CREATE_COMPLETE' && $status !== 'UPDATE_COMPLETE') {
            sleep(5);
            $status = $this->getStackStatus($stackName);
        };

        $result = $this->cloudformationClient->describeStackResource([
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
            $this->cloudformationClient->describeStacks([
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
            $this->cloudformationClient->validateTemplate([
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
                $this->cloudformationClient->updateStack([
                    'StackName' => $stackName,
                    'TemplateBody' => json_encode($template)
                ]);
            } else {
                $this->cloudformationClient->createStack([
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
        $result = $this->cloudformationClient->describeStacks([
            'StackName' => $stackName
        ]);

        return $result->get('Stacks')[0]['StackStatus'];
    }
}
