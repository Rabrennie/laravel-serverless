<?php
namespace LaravelServerless\Test;

use LaravelServerless\Providers\ServerlessServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [ServerlessServiceProvider::class];
    }
    
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.name', 'serverless-test');
        $app['config']->set('serverless.aws', [
            'key' => '123',
            'secret' => '321',
            'region' => 'eu-west-1'
        ]);
        $app['config']->set('serverless.environment', [
            'APP_STORAGE' => '/tmp'
        ]);
    }
}
