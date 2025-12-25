<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Commands;

use Illuminate\Console\Command;
use Vasoft\LaravelVersionIncrement\Exceptions\ModuleException;

class NoCommitCommand extends Command
{
    protected $signature = 'vs-version:no-commit';
    protected $description = 'Process without making a commit and version tag';

    public function handle(?CommandRunner $runner = null): int
    {
        $runner ??= new CommandRunner();
        if ($this->argument('type')) {
            $type = (string) $this->argument('type');
        } else {
            $type = '';
        }

        try {
            $runner->noCommit($type);
        } catch (ModuleException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
