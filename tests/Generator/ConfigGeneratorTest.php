<?php

namespace LaravelServerless\Test\Generator;

use LaravelServerless\Generator\ConfigGenerator;
use LaravelServerless\Test\TestCase;
use Symfony\Component\Yaml\Yaml;

class ConfigGeneratorTest extends TestCase
{
    public function testGenerateReturnsString()
    {
        $result = ConfigGenerator::generate();
        $this->assertIsString('string', $result);
    }

    public function testGenerateReturnsValidYAML()
    {
        $result = ConfigGenerator::generate();
        $this->assertNotNull(Yaml::parse($result));
    }

    public function testGenerateIncludesAppName()
    {
        $result = ConfigGenerator::generate();
        $this->assertStringContainsString('service: serverless-test', $result);
    }

    public function testGenerateIncludesProvider()
    {
        $result = ConfigGenerator::generate();
        $expected = Yaml::dump([
            'provider' => [
                'name' => 'aws',
                'region' => 'eu-west-1',
                'stage' => 'testing',
                'runtime' => 'provided',
                'environment' => [
                    'APP_STORAGE' => '/tmp'
                ],
                'iamRoleStatements' => null
            ]
            ], 10);
        $this->assertStringContainsString($expected, $result);
    }
}