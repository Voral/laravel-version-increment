<?php

namespace Vasoft\LaravelVersionIncrement;

use Illuminate\Support\ServiceProvider;
use Vasoft\LaravelVersionIncrement\Commands\ListCommand;
use Vasoft\LaravelVersionIncrement\Commands\VersionIncrementCommand;

class VersionIncrementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ListCommand::class,
                VersionIncrementCommand::class,
            ]);
        }
    }
}