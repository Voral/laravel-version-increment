<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Commands;

use Illuminate\Console\Command;
use Vasoft\LaravelVersionIncrement\Exceptions\ModuleException;

class IncrementCommand extends Command
{
    protected $signature = 'vs-version:increment
                            {type? : One of: major, minor, patch, or leave empty for auto}';

    protected $description = 'Increment project version using voral/vs-version-increment';

    public function handle(?CommandRunner $runner = null): int
    {
        $runner ??= new CommandRunner();
        $type = is_string($this->argument('type')) ? $this->argument('type') : '';

        try {
            $runner->increment($type);
            $this->output->write($runner->getLastOutput());
        } catch (ModuleException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
