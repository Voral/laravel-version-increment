<?php

declare(strict_types=1);

namespace Vasoft\Tests\LaravelVersionIncrement\Commands;

use Illuminate\Console\OutputStyle;
use phpmock\phpunit\PHPMock;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
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
        $command = new ListCommand();
        $command->setLaravel($this->app);
        $laravelOutput = new OutputStyle(new ArrayInput([]), new BufferedOutput());
        $command->setOutput($laravelOutput);

        $passthru = $this->getFunctionMock(self::TESTED_NAMESPACE, 'passthru');
        $passthru->expects(self::once())->with('./vendor/bin/vs-version-increment --list');

        $exitCode = $command->handle();

        self::assertSame(Command::SUCCESS, $exitCode);
    }
}
