<?php

declare(strict_types=1);

namespace Vasoft\Tests\LaravelVersionIncrement;

use Vasoft\LaravelVersionIncrement\Commands\DebugCommand;
use Vasoft\LaravelVersionIncrement\Commands\ListCommand;
use Vasoft\LaravelVersionIncrement\Commands\NoCommitCommand;
use Vasoft\LaravelVersionIncrement\VersionIncrementServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Vasoft\LaravelVersionIncrement\Commands\IncrementCommand;

/**
 * @internal
 *
 * @coversDefaultClass \Vasoft\LaravelVersionIncrement\VersionIncrementServiceProvider
 */
final class VersionIncrementServiceProviderTest extends TestCase
{
    public function testRegistersVersionIncrementCommandInConsoleMode(): void
    {
        $this->app->register(VersionIncrementServiceProvider::class);

        $commands = $this->app->make(Kernel::class)->all();

        self::assertArrayHasKey('vs-version:increment', $commands);
        self::assertInstanceOf(IncrementCommand::class, $commands['vs-version:increment']);

        self::assertArrayHasKey('vs-version:list', $commands);
        self::assertInstanceOf(ListCommand::class, $commands['vs-version:list']);

        self::assertArrayHasKey('vs-version:debug', $commands);
        self::assertInstanceOf(DebugCommand::class, $commands['vs-version:debug']);

        self::assertArrayHasKey('vs-version:no-commit', $commands);
        self::assertInstanceOf(NoCommitCommand::class, $commands['vs-version:no-commit']);
    }
}
