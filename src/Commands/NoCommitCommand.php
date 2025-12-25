<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Commands;

use Illuminate\Console\Command;
use Vasoft\LaravelVersionIncrement\Exceptions\ModuleException;

class NoCommitCommand extends Command
{
    protected $signature = 'vs-version:no-commit
                            {type? : One of: major, minor, patch, or leave empty for auto}';
    protected $description = 'Process without making a commit and version tag';

    public function handle(?CommandRunner $runner = null): int
    {
        $runner ??= new CommandRunner();
        $type = is_string($this->argument('type')) ? $this->argument('type') : '';

        try {
            $runner->noCommit($type);
            $this->output->write($runner->getLastOutput());
        } catch (ModuleException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
