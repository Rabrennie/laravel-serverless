<?php

namespace LaravelServerless\Generator;

class PackageConfig
{
    public $exclude;

    public function __construct()
    {
        $this->exclude = config('serverless.package.exclude');
    }
}
