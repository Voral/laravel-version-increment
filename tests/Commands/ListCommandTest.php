<?php

declare(strict_types=1);

namespace Vasoft\Tests\LaravelVersionIncrement\Commands;

use phpmock\phpunit\PHPMock;
use Vasoft\LaravelVersionIncrement\Commands\CommandRunner;
use Vasoft\LaravelVersionIncrement\Commands\ListCommand;
use Vasoft\Tests\LaravelVersionIncrement\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * @internal
 *
 * @coversDefaultClass  \Vasoft\LaravelVersionIncrement\Commands\ListCommand
 */
final class ListCommandTest extends TestCase
{
    use PHPMock;

    private const TESTED_NAMESPACE = 'Vasoft\LaravelVersionIncrement\Commands';

    public function testHasCorrectSignatureAndDescription(): void
    {
        $command = new ListCommand();

        self::assertSame('vs-version:list', $command->getName());
        self::assertSame(
            'List registered commit types and scopes',
            $command->getDescription(),
        );
    }

    public function testHandlesCorrectly(): void
    {
        $runner = $this->createMock(CommandRunner::class);
        $runner->expects(self::once())->method('list');

        $command = new ListCommand();
        $exitCode = $command->handle($runner);

        self::assertSame(Command::SUCCESS, $exitCode);
    }
}
