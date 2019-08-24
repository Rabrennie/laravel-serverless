<?php

namespace LaravelServerless\Deployment;

interface iDeploymentStep
{
    public function execute():string;
}
