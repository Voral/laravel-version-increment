<?php

declare(strict_types=1);

namespace Vasoft\Tests\LaravelVersionIncrement\Commands;

use Illuminate\Console\OutputStyle;
use phpmock\phpunit\PHPMock;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Vasoft\LaravelVersionIncrement\Commands\CommandRunner;
use Vasoft\LaravelVersionIncrement\Commands\DebugCommand;
use Vasoft\LaravelVersionIncrement\Exceptions\ProcessException;
use Vasoft\Tests\LaravelVersionIncrement\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * @internal
 *
 * @coversNothing
 */
final class DebugCommandTest extends TestCase
{
    use PHPMock;

    private const TESTED_NAMESPACE = 'Vasoft\LaravelVersionIncrement\Commands';

    public function testHasCorrectSignatureAndDescription(): void
    {
        $command = new DebugCommand();

        self::assertSame('vs-version:debug', $command->getName());
        self::assertSame(
            'Preview the changes without actually applying them',
            $command->getDescription(),
        );
    }

    public function testHandlesCorrectly(): void
    {
        $runner = self::createMock(CommandRunner::class);
        $runner->expects(self::once())
            ->method('debug');

        $command = $this->getMockBuilder(DebugCommand::class)
            ->onlyMethods(['argument'])
            ->getMock();

        $command->method('argument')
            ->with('type')
            ->willReturn('major');

        $command->setLaravel($this->app);
        $laravelOutput = new OutputStyle(new ArrayInput([]), new BufferedOutput());
        $command->setOutput($laravelOutput);

        $exitCode = $command->handle($runner);

        self::assertSame(Command::SUCCESS, $exitCode);
    }

    public function testHandlesFailed(): void
    {
        $exceptionMessage = 'Failure';
        $runner = self::createMock(CommandRunner::class);
        $runner->expects(self::once())
            ->method('debug')
            ->willThrowException(new ProcessException($exceptionMessage));

        $command = $this->getMockBuilder(DebugCommand::class)
            ->onlyMethods(['argument', 'error'])
            ->getMock();

        $command->method('argument')
            ->with('type')
            ->willReturn('major');
        $command->method('error')
            ->with($exceptionMessage);

        $command->setLaravel($this->app);
        $laravelOutput = new OutputStyle(new ArrayInput([]), new BufferedOutput());
        $command->setOutput($laravelOutput);

        $exitCode = $command->handle($runner);

        self::assertSame(Command::FAILURE, $exitCode);
    }
}
