<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Commands;

use Vasoft\LaravelVersionIncrement\Exceptions\ProcessException;
use Vasoft\LaravelVersionIncrement\Exceptions\ToolStartFailedException;

class CommandRunner
{
    public const BIN = './vendor/bin/vs-version-increment';
    private string $lastOutput = '';

    public function list(): void
    {
        $this->lastOutput = '';
        passthru(self::BIN . ' --list');
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    public function noCommit(string $type): void
    {
        $args = ['--no-commit'];
        if (!empty($type)) {
            $args[] = $type;
        }
        $this->run($args);
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    public function debug(string $type): void
    {
        $args = ['--debug'];
        if (!empty($type)) {
            $args[] = $type;
        }

        $this->run($args);
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    public function increment(string $type): void
    {
        $this->run(empty($type) ? [] : [$type]);
    }

    /**
     * @param array<string> $args
     *
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    private function run(array $args): void
    {
        $this->lastOutput = '';
        $command = array_merge([self::BIN], $args);
        $process = proc_open(
            implode(' ', array_map('escapeshellarg', $command)),
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
        );

        if (!is_resource($process)) {
            throw new ToolStartFailedException();
        }

        fclose($pipes[0]);
        $this->lastOutput = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if (0 !== $exitCode) {
            throw new ProcessException(false === $stderr ? '' : $stderr);
        }
    }

    public function getLastOutput(): string
    {
        return $this->lastOutput;
    }
}
