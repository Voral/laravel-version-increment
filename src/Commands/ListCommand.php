<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Commands;

use Illuminate\Console\Command;

class ListCommand extends Command
{
    protected $signature = 'vs-version:list';
    protected $description = 'List registered commit types and scopes';

    public function handle(): int
    {
        passthru('./vendor/bin/vs-version-increment --list');

        return self::SUCCESS;
    }
}
