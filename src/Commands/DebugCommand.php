<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Commands;

use Illuminate\Console\Command;
use Vasoft\LaravelVersionIncrement\Exceptions\ModuleException;

class DebugCommand extends Command
{
    protected $signature = 'vs-version:debug
                            {type? : One of: major, minor, patch, or leave empty for auto}';
    protected $description = 'Preview the changes without actually applying them';

    public function handle(?CommandRunner $runner = null): int
    {
        $runner ??= new CommandRunner();
        $type = is_string($this->argument('type')) ? $this->argument('type') : '';

        try {
            $runner->debug($type);
            $this->output->write($runner->getLastOutput());
        } catch (ModuleException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
