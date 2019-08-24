<?php

namespace LaravelServerless\Generator;

class ResourcesConfig
{
    public $Resources;

    public function __construct()
    {
        $serviceName = strtolower(config('app.name'));
        $stage = strtolower(config('app.env'));

        $assets = new ResourceConfig('AWS::S3::Bucket', [
            'BucketName' => "{$serviceName}-client-{$stage}",
            'CorsConfiguration' => [
                'CorsRules' => [[
                    'AllowedHeaders' => ['*'],
                    'AllowedMethods' => ['GET'],
                    'AllowedOrigins' => ['*']
                ]]
            ]
        ]);

        $assetsBucketPolicy = new ResourceConfig('AWS::S3::BucketPolicy', [
            'Bucket' => ['Ref' => 'Assets'],
            'PolicyDocument' => [
                'Statement' => [[
                    'Effect' => 'Allow',
                    'Principal' => '*',
                    'Action' => 's3:GetObject',
                    'Resource' => [
                        'Fn::Join:' => [
                            '',
                            [
                                'arn:aws:s3:::',
                                ['Ref' => 'Assets'],
                                '/*'
                            ]
                        ]
                    ]
                ]]
            ]
        ]);

        $websiteCDN = new ResourceConfig('AWS::CloudFront::Distribution', [
            'DistributionConfig' => [
                'Enabled' => true,
                'PriceClass' => 'PriceClass_100',
                'HttpVersion' => 'http2',
                'Origins' => [
                    [
                        'Id' => 'Website',
                        'DomainName' => '#{ApiGatewayRestApi}.execute-api.#{AWS::Region}.amazonaws.com',
                        'OriginPath' => "/{$stage}",
                        'CustomOriginConfig' => [
                            'OriginProtocolPolicy' => 'https-only'
                        ]
                    ],
                    [
                        'Id' => 'Assets',
                        'DomainName' => '#{Assets}.s3.amazonaws.com',
                        'CustomOriginConfig' => [
                            'OriginProtocolPolicy' => 'https-only'
                        ]
                    ]
                ],
                'DefaultCacheBehavior' => [
                    'TargetOriginId' => 'Website',
                    'AllowedMethods' => ['GET', 'HEAD', 'OPTIONS', 'PUT', 'POST', 'PATCH', 'DELETE'],
                    'DefaultTTL' => 0,
                    'MinTTL' => 0,
                    'MaxTTL' => 0,
                    'ForwardedValues' => [
                        'QueryString' => true,
                        'Cookies' => ['Forward' => 'all'],
                        'Headers' => [
                            'Accept',
                            'Accept-Language',
                            'Origin',
                            'Referer'
                        ]
                    ],
                    'ViewerProtocolPolicy' => 'redirect-to-https'
                ],
                'CacheBehaviors' => [[
                    'TargetOriginId' => 'Assets',
                    'AllowedMethods' => ['GET', 'HEAD'],
                    'PathPattern' => 'assets/*',
                    'ForwardedValues' => [
                        'QueryString' => true,
                        'Cookies' => ['Forward' => 'none']
                    ],
                    'ViewerProtocolPolicy' => 'redirect-to-https',
                    'Compress' => true
                ]],
                'CustomErrorResponses' => [
                    [
                        'ErrorCode' => 500,
                        'ErrorCachingMinTTL' => 0
                    ],
                    [
                        'ErrorCode' => 504,
                        'ErrorCachingMinTTL' => 0
                    ]
                ]
            ]
        ]);

        $sessionsTable = new ResourceConfig('AWS::DynamoDB::Table', [
            'TableName' => "{$serviceName}-sessions-{$stage}",
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                ]
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH'
                ]
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 1,
                'WriteCapacityUnits' => 1,
            ]
        ]);

        $this->Resources = [
            'Assets' => $assets,
            'AssetsBucketPolicy' => $assetsBucketPolicy,
            'WebsiteCDN' => $websiteCDN,
            'SessionsTable' => $sessionsTable
        ];
    }
}
