<?php

declare(strict_types=1);

namespace Vasoft\Tests\LaravelVersionIncrement\Commands;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Vasoft\LaravelVersionIncrement\Commands\CommandRunner;
use Vasoft\LaravelVersionIncrement\Exceptions\ProcessException;
use Vasoft\LaravelVersionIncrement\Exceptions\ToolStartFailedException;

/**
 * @internal
 *
 * @coversDefaultClass \Vasoft\LaravelVersionIncrement\Commands\CommandRunner
 */
final class CommandRunnerTest extends TestCase
{
    use PHPMock;

    private const TESTED_NAMESPACE = 'Vasoft\LaravelVersionIncrement\Commands';
    private const BIN = './vendor/bin/vs-version-increment';

    public function testList(): void
    {
        $runner = new CommandRunner();

        $passthru = $this->getFunctionMock(self::TESTED_NAMESPACE, 'passthru');
        $passthru->expects(self::once())->with(self::BIN . ' --list');

        $runner->list();
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    #[DataProvider('provideIncrementCases')]
    public function testIncrement(string $type): void
    {
        $command = array_filter([
            self::BIN,
            $type,
        ]);
        $expected = implode(' ', array_map('escapeshellarg', $command));
        $runner = new CommandRunner();

        $fClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'fclose');
        $fClose->expects(self::exactly(3))->willReturn(true);

        $isResource = $this->getFunctionMock(self::TESTED_NAMESPACE, 'is_resource');
        $isResource->expects(self::once())->willReturn(true);

        $procClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_close');
        $procClose->expects(self::once())->willReturn(0);
        $streamGetContents = $this->getFunctionMock(self::TESTED_NAMESPACE, 'stream_get_contents');
        $streamGetContents->expects(self::exactly(2))
            ->willReturnOnConsecutiveCalls('', '');

        $procOpen = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_open');
        $procOpen->expects(self::once())
            ->with(
                self::stringContains($expected),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
            )
            ->willReturnCallback(static function ($cmd, $desc, &$pipes) {
                $pipes = [fopen('php://memory', 'r'), fopen('php://memory', 'r'), fopen('php://memory', 'r')];

                return true;
            });

        $runner->increment($type);
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    #[DataProvider('provideIncrementCases')]
    public function testStdError(): void
    {
        $command = array_filter([self::BIN]);
        $expected = implode(' ', array_map('escapeshellarg', $command));
        $expectedErrorMessage = 'Std error output';

        $runner = new CommandRunner();

        $fClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'fclose');
        $fClose->expects(self::exactly(3))->willReturn(true);

        $isResource = $this->getFunctionMock(self::TESTED_NAMESPACE, 'is_resource');
        $isResource->expects(self::once())->willReturn(true);

        $procClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_close');
        $procClose->expects(self::once())->willReturn(1);
        $streamGetContents = $this->getFunctionMock(self::TESTED_NAMESPACE, 'stream_get_contents');

        $streamGetContents->expects(self::exactly(2))
            ->willReturnOnConsecutiveCalls('', $expectedErrorMessage);

        $procOpen = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_open');
        $procOpen->expects(self::once())
            ->with(
                self::stringContains($expected),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
            )
            ->willReturnCallback(static function ($cmd, $desc, &$pipes) {
                $pipes = [fopen('php://memory', 'r'), fopen('php://memory', 'r'), fopen('php://memory', 'r')];

                return true;
            });
        self::expectException(ProcessException::class);
        self::expectExceptionMessage($expectedErrorMessage);
        $runner->debug('');
    }
    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    public function testProcOpenFiled(): void
    {
        $command = array_filter([self::BIN]);
        $expected = implode(' ', array_map('escapeshellarg', $command));
        $expectedErrorMessage = 'Std error output';

        $runner = new CommandRunner();

        $fClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'fclose');
        $fClose->expects(self::never());

        $isResource = $this->getFunctionMock(self::TESTED_NAMESPACE, 'is_resource');
        $isResource->expects(self::once())->willReturn(false);

        $procClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_close');
        $procClose->expects(self::never());
        $streamGetContents = $this->getFunctionMock(self::TESTED_NAMESPACE, 'stream_get_contents');
        $streamGetContents->expects(self::never());

        $procOpen = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_open');
        $procOpen->expects(self::once())
            ->with(
                self::stringContains($expected),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
            )
            ->willReturnCallback(static function ($cmd, $desc, &$pipes) {
                $pipes = [fopen('php://memory', 'r'), fopen('php://memory', 'r'), fopen('php://memory', 'r')];

                return true;
            });
        self::expectException(ToolStartFailedException::class);
        self::expectExceptionMessage('Failed to start vs-version-incrementor');
        $runner->debug('');
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    #[DataProvider('provideIncrementCases')]
    public function testStdErrorEmpty(): void
    {
        $command = array_filter([self::BIN]);
        $expected = implode(' ', array_map('escapeshellarg', $command));

        $runner = new CommandRunner();

        $fClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'fclose');
        $fClose->expects(self::exactly(3))->willReturn(true);

        $isResource = $this->getFunctionMock(self::TESTED_NAMESPACE, 'is_resource');
        $isResource->expects(self::once())->willReturn(true);

        $procClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_close');
        $procClose->expects(self::once())->willReturn(1);
        $streamGetContents = $this->getFunctionMock(self::TESTED_NAMESPACE, 'stream_get_contents');

        $streamGetContents->expects(self::exactly(2))
            ->willReturnOnConsecutiveCalls('', '');

        $procOpen = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_open');
        $procOpen->expects(self::once())
            ->with(
                self::stringContains($expected),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
            )
            ->willReturnCallback(static function ($cmd, $desc, &$pipes) {
                $pipes = [fopen('php://memory', 'r'), fopen('php://memory', 'r'), fopen('php://memory', 'r')];

                return true;
            });
        self::expectException(ProcessException::class);
        self::expectExceptionMessage('Unknow execute error');
        $runner->debug('');
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    #[DataProvider('provideIncrementCases')]
    public function testDebugAndOutput(string $type): void
    {
        $command = array_filter([
            self::BIN,
            '--debug',
            $type,
        ]);
        $expected = implode(' ', array_map('escapeshellarg', $command));
        $expectedOutput = 'Test ' . $type;
        $runner = new CommandRunner();

        $fClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'fclose');
        $fClose->expects(self::exactly(3))->willReturn(true);

        $isResource = $this->getFunctionMock(self::TESTED_NAMESPACE, 'is_resource');
        $isResource->expects(self::once())->willReturn(true);

        $procClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_close');
        $procClose->expects(self::once())->willReturn(0);
        $streamGetContents = $this->getFunctionMock(self::TESTED_NAMESPACE, 'stream_get_contents');

        $streamGetContents->expects(self::exactly(2))
            ->willReturnOnConsecutiveCalls($expectedOutput, '');

        $procOpen = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_open');
        $procOpen->expects(self::once())
            ->with(
                self::stringContains($expected),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
            )
            ->willReturnCallback(static function ($cmd, $desc, &$pipes) {
                $pipes = [fopen('php://memory', 'r'), fopen('php://memory', 'r'), fopen('php://memory', 'r')];

                return true;
            });

        $runner->debug($type);
        self::assertSame($expectedOutput, $runner->getLastOutput());
    }

    /**
     * @throws ProcessException
     * @throws ToolStartFailedException
     */
    #[DataProvider('provideIncrementCases')]
    public function testNoCommit(string $type): void
    {
        $command = array_filter([
            self::BIN,
            '--no-commit',
            $type,
        ]);
        $expected = implode(' ', array_map('escapeshellarg', $command));
        $runner = new CommandRunner();

        $fClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'fclose');
        $fClose->expects(self::exactly(3))->willReturn(true);

        $isResource = $this->getFunctionMock(self::TESTED_NAMESPACE, 'is_resource');
        $isResource->expects(self::once())->willReturn(true);

        $procClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_close');
        $procClose->expects(self::once())->willReturn(0);
        $streamGetContents = $this->getFunctionMock(self::TESTED_NAMESPACE, 'stream_get_contents');
        $streamGetContents->expects(self::exactly(2))
            ->willReturnOnConsecutiveCalls('', '');

        $procOpen = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_open');
        $procOpen->expects(self::once())
            ->with(
                self::stringContains($expected),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
                self::anything(),
            )
            ->willReturnCallback(static function ($cmd, $desc, &$pipes) {
                $pipes = [fopen('php://memory', 'r'), fopen('php://memory', 'r'), fopen('php://memory', 'r')];

                return true;
            });

        $runner->noCommit($type);
    }

    public static function provideIncrementCases(): iterable
    {
        return [
            [''],
            ['major'],
            ['minor'],
            ['patch'],
            ['patch'],
        ];
    }
}
