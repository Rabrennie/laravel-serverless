<?php

namespace LaravelServerless\Generator;

class CloudFormationTypes
{
    const S3_BUCKET = 'AWS::S3::Bucket';
    const LOGS_LOG_GROUP = 'AWS::Logs::LogGroup';
    const IAM_ROLE = 'AWS::IAM::Role';
    const LAMBDA_FUNCTION = 'AWS::Lambda::Function';
}
