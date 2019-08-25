<?php

namespace LaravelServerless\Console\Commands;

use Illuminate\Console\Command;
use LaravelServerless\Deployment\CreateCloudformationStack;
use LaravelServerless\Deployment\CreateDeploymentBucket;
use LaravelServerless\Deployment\DeploymentState;
use LaravelServerless\Deployment\DeploymentStep;
use LaravelServerless\Deployment\PackageApp;
use LaravelServerless\Deployment\UploadPackagedApp;

class ServerlessDeployCommand extends Command
{

    protected $signature = 'serverless:deploy';
    protected $description = 'Deploys service to remote serverless service.';
    protected $storagePath;

    protected $steps = [
        PackageApp::class,
        CreateDeploymentBucket::class,
        UploadPackagedApp::class,
        CreateCloudformationStack::class,
    ];

    public function handle()
    {
        $deploymentState = new DeploymentState();
        $deploymentState->set('executionTime', time());

        foreach ($this->steps as $stepClass) {
            /** @var DeploymentStep $class */
            $step = new $stepClass($deploymentState);
            try {
                $result = $step->execute();
                $this->info($result);
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->error($e->getMessage());
            }
            echo json_encode($deploymentState, JSON_PRETTY_PRINT) . "\n";
        }
    }
}
