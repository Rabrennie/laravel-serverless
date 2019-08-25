<?php

namespace LaravelServerless\Generator;

use LaravelServerless\Generator\CloudFormationTypes;
use LaravelServerless\Generator\ResourceTemplate;

class LambdaExecutionIamRoleTemplate extends ResourceTemplate
{
    public function __construct()
    {
        parent::__construct(CloudFormationTypes::IAM_ROLE, [
            'AssumeRolePolicyDocument' => [
                'Version' => '2012-10-17',
                'Statement' => [
                    [
                        'Effect' => 'Allow',
                        'Principal' => [
                            'Service' => ['lambda.amazonaws.com']
                        ],
                        'Action' => ['sts:AssumeRole']
                    ]
                ]
            ],
            'Policies' => [
                [
                    'PolicyName' => [
                        'Fn::Join' => [
                            '-',
                            [
                                strtolower(config('app.env')),
                                strtolower(config('app.name')),
                                'lambda'
                            ]
                        ]
                    ],
                    'PolicyDocument' => [
                        'Version' => '2012-10-17',
                        'Statement' => [
                            [
                                'Effect' => 'Allow',
                                'Action' => ['logs:CreateLogStream'],
                                'Resource' => [
                                    [
                                        'Fn::Sub' => 'arn:${AWS::Partition}:logs:${AWS::Region}:${AWS::AccountId}:log-group:/aws/lambda/laravel-dev*:*'
                                    ]
                                ]
                            ],
                            [
                                'Effect' => 'Allow',
                                'Action' => ['logs:PutLogEvents'],
                                'Resource' => [
                                    [
                                        'Fn::Sub' => 'arn:${AWS::Partition}:logs:${AWS::Region}:${AWS::AccountId}:log-group:/aws/lambda/laravel-dev*:*:*'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'Path' => '/',
            'RoleName' => [
                'Fn::Join' => [
                    '-',
                    [
                        strtolower(config('app.name')),
                        strtolower(config('app.env')),
                        [
                            'Ref' => 'AWS::Region'
                        ],
                        'lambdaRole'
                    ]
                ]
            ]
        ]);
    }
}
