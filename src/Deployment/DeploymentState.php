<?php

namespace LaravelServerless\Deployment;

class DeploymentState
{
    public $state = [];

    public function set($key, $value)
    {
        $this->state[$key] = $value;
    }

    public function get($key)
    {
        if (isset($this->state[$key])) {
            return $this->state[$key];
        }

        return null;
    }
}
