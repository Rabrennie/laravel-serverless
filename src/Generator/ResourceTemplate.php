<?php

namespace LaravelServerless\Generator;

class ResourceTemplate
{
    public $Type;
    public $Properties;

    public function __construct(string $type, array $properties)
    {
        $this->Type = $type;
        $this->Properties = $properties;
    }
}
