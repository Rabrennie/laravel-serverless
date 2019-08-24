<?php

namespace LaravelServerless\Console\Commands;

use Illuminate\Console\Command;
use LaravelServerless\Generator\ConfigGenerator;

class ServerlessDeployCommand extends Command
{

    protected $signature = 'serverless:deploy';
    protected $description = 'Deploys service to remote serverless service.';
    protected $storagePath;

    public function __construct()
    {
        parent::__construct();

        $this->storagePath = storage_path("laravel-serverless/");
        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath);
        }
    }

    public function handle()
    {
        $config = ConfigGenerator::generate();
        file_put_contents($this->getConfigPath(), $config);

        $result = $this->deploy();
        $this->info($result);
    }

    public function getConfigPath()
    {
        return "{$this->storagePath}/serverless.yml";
    }

    public function getPackagePath()
    {
        return "{$this->storagePath}/.serverless/";
    }

    public function getRelativePath($path)
    {
        return './' . str_replace(base_path(), '', $path);
    }

    public function deploy()
    {
        chdir(base_path());
        $configPath = $this->getRelativePath($this->getConfigPath());
        $packagePath = $this->getRelativePath($this->getPackagePath());
        return shell_exec("serverless deploy --config {$configPath} --package {$packagePath}");
    }
}
