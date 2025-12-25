<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement;

use Illuminate\Support\ServiceProvider;
use Vasoft\LaravelVersionIncrement\Commands\DebugCommand;
use Vasoft\LaravelVersionIncrement\Commands\ListCommand;
use Vasoft\LaravelVersionIncrement\Commands\IncrementCommand;
use Vasoft\LaravelVersionIncrement\Commands\NoCommitCommand;

class VersionIncrementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ListCommand::class,
                IncrementCommand::class,
                DebugCommand::class,
                NoCommitCommand::class,
            ]);
        }
    }
}
