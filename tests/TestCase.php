<?php

namespace Vasoft\Tests\LaravelVersionIncrement;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function defineEnvironment($app): void
    {
        $app['runningInConsole'] = false;
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            \Vasoft\LaravelVersionIncrement\VersionIncrementServiceProvider::class,
        ];
    }
}