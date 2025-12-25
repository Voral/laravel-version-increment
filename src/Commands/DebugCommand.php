<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Commands;

use Illuminate\Console\Command;
use Vasoft\LaravelVersionIncrement\Exceptions\ModuleException;

class DebugCommand extends Command
{
    protected $signature = 'vs-version:debug';
    protected $description = 'Preview the changes without actually applying them';

    public function handle(?CommandRunner $runner = null): int
    {
        $runner ??= new CommandRunner();
        if ($this->argument('type')) {
            $type = (string) $this->argument('type');
        } else {
            $type = '';
        }

        try {
            $runner->debug($type);
        } catch (ModuleException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
