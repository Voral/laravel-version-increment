<?php

namespace Vasoft\LaravelVersionIncrement;

use Illuminate\Support\ServiceProvider;
use Vasoft\LaravelVersionIncrement\Commands\DebugCommand;
use Vasoft\LaravelVersionIncrement\Commands\ListCommand;
use Vasoft\LaravelVersionIncrement\Commands\IncrementCommand;

class VersionIncrementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ListCommand::class,
                DebugCommand::class,
                IncrementCommand::class,
            ]);
        }
    }
}