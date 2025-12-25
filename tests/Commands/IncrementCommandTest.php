<?php

declare(strict_types=1);

namespace Vasoft\Tests\LaravelVersionIncrement\Commands;

use Illuminate\Console\OutputStyle;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Vasoft\LaravelVersionIncrement\Commands\CommandRunner;
use Vasoft\LaravelVersionIncrement\Commands\IncrementCommand;
use Vasoft\LaravelVersionIncrement\Exceptions\ProcessException;
use Vasoft\Tests\LaravelVersionIncrement\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * @internal
 *
 * @coversDefaultClass \Vasoft\LaravelVersionIncrement\Commands\IncrementCommand
 */
final class IncrementCommandTest extends TestCase
{
    use PHPMock;

    private const TESTED_NAMESPACE = 'Vasoft\LaravelVersionIncrement\Commands';

    public function testHasCorrectSignatureAndDescription(): void
    {
        $command = new IncrementCommand();

        $argument = $command->getDefinition()->getArgument('type');
        self::assertTrue(false === $argument->isRequired());
        self::assertSame(
            'One of: major, minor, patch, or leave empty for auto',
            $argument->getDescription(),
        );
        self::assertSame('vs-version:increment', $command->getName());
        self::assertSame(
            'Increment project version using voral/vs-version-increment',
            $command->getDescription(),
        );
    }

    #[DataProvider('provideHandlesCorrectlyCases')]
    public function testHandlesCorrectly(): void
    {
        $runner = self::createMock(CommandRunner::class);
        $runner->expects(self::once())
            ->method('increment');

        $command = $this->getMockBuilder(IncrementCommand::class)
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

    public static function provideHandlesCorrectlyCases(): iterable
    {
        return [
            [''],
            ['major'],
            ['minor'],
            ['patch'],
        ];
    }

    public function testHandlesFailed(): void
    {
        $exceptionMessage = 'Failure';
        $runner = self::createMock(CommandRunner::class);
        $runner->expects(self::once())
            ->method('increment')
            ->willThrowException(new ProcessException($exceptionMessage));

        $command = $this->getMockBuilder(IncrementCommand::class)
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
