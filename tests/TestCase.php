<?php

declare(strict_types=1);

namespace Vasoft\Tests\LaravelVersionIncrement;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vasoft\LaravelVersionIncrement\VersionIncrementServiceProvider;

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
            VersionIncrementServiceProvider::class,
        ];
    }
}
