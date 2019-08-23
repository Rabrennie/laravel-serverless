<?php

namespace LaravelServerless\Generator;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Yaml\Yaml;

class ConfigGenerator
{
    public static function generate()
    {
        $serverlessConfig = new ServerlessConfig();
        $yaml = Yaml::dump(json_decode(json_encode((array)$serverlessConfig), true), 10);
        return $yaml;
    }
}
