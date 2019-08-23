<?php

namespace LaravelServerless\Generator;

class FunctionConfig
{
    public $handler;
    public $timeout;
    public $layers;
    public $events;

    public function __construct(string $handler, int $timeout, array $layers = [], array $events = [])
    {
        $this->handler = $handler;
        $this->timeout = $timeout;
        $this->layers = $layers;
        $this->events = $events;
    }
}
