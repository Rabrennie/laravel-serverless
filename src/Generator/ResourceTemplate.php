<?php

namespace LaravelServerless\Generator;

class ResourceTemplate
{
    public $Type;
    public $Properties;
    public $DependsOn;

    public function __construct(string $type, array $properties, array $dependsOn = [])
    {
        $this->Type = $type;
        $this->Properties = $properties;
        $this->DependsOn = $dependsOn;
    }
}
