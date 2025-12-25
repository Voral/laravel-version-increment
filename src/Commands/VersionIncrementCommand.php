<?php

namespace Vasoft\LaravelVersionIncrement\Commands;

use Illuminate\Console\Command;
use Vasoft\VersionIncrement\SemanticVersionUpdater;
use Vasoft\VersionIncrement\Config;

class VersionIncrementCommand extends Command
{
    protected $signature = 'vs-version:increment
                            {type? : One of: major, minor, patch, or leave empty for auto}
                            {--debug : Preview changes without applying them}
                            {--no-commit : Skip Git commit and tag}
                            {--list : List registered commit types}';

    protected $description = 'Increment project version using voral/vs-version-increment';

    public function handle(): int
    {
        if ($this->option('list')) {
            passthru('./vendor/bin/vs-version-increment --list');
            return self::SUCCESS;
        }

        $args = [];
        if ($type = $this->argument('type')) {
            $args[] = $type;
        }

        if ($this->option('debug')) {
            $args[] = '--debug';
        }

        if ($this->option('no-commit')) {
            $args[] = '--no-commit';
        }

        $command = array_merge(['./vendor/bin/vs-version-increment'], $args);
        $process = proc_open(
            implode(' ', array_map('escapeshellarg', $command)),
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes
        );

        if (!is_resource($process)) {
            $this->error('Failed to start vs-version-incrementor');
            return self::FAILURE;
        }

        fclose($pipes[0]);
        $this->output->write(stream_get_contents($pipes[1]));
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($stderr !== '' && $exitCode !== 0) {
            $this->error(rtrim($stderr));
        }

        return $exitCode === 0 ? self::SUCCESS : self::FAILURE;
    }
}