<?php

namespace LaravelServerless\Generator;

use LaravelServerless\Generator\CloudFormationTypes;
use LaravelServerless\Generator\ResourceTemplate;

class WebsiteLogGroupTemplate extends ResourceTemplate
{
    public function __construct()
    {
        $lambdaName = strtolower(config('app.name') . '-' . config('app.env') . '-website');
        parent::__construct(CloudFormationTypes::LOGS_LOG_GROUP, [
            'LogGroupName' => "/aws/lambda/{$lambdaName}"
        ]);
    }
}
