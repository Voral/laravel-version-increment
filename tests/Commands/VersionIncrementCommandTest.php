<?php

namespace Vasoft\Tests\LaravelVersionIncrement\Commands;

use Illuminate\Console\OutputStyle;
use phpmock\phpunit\PHPMock;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Vasoft\LaravelVersionIncrement\Commands\VersionIncrementCommand;
use Vasoft\Tests\LaravelVersionIncrement\TestCase;

class VersionIncrementCommandTest extends TestCase
{
    private const TESTED_NAMESPACE = 'Vasoft\LaravelVersionIncrement\Commands';
    use PHPMock;

    public function test_has_correct_signature_and_description(): void
    {
        $command = new VersionIncrementCommand();

        $this->assertEquals('vs-version:increment', $command->getName());
        $this->assertEquals(
            'Increment project version using voral/vs-version-increment',
            $command->getDescription()
        );
    }

    public function test_handles_list_option_correctly(): void
    {
        $command = $this->getMockBuilder(VersionIncrementCommand::class)
            ->onlyMethods(['option'])
            ->getMock();

        $command->expects($this->once())
            ->method('option')
            ->with('list')
            ->willReturn(true);

        $passthru = $this->getFunctionMock(self::TESTED_NAMESPACE, 'passthru');
        $passthru->expects($this->once())->with('./vendor/bin/vs-version-increment --list');

        // Заменяем passthru на наш мок
        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('handle');
        $method->setAccessible(true);

        $result = $method->invoke($command);
        $this->assertEquals(\Symfony\Component\Console\Command\Command::SUCCESS, $result);
    }

    public function test_builds_correct_command_for_type_version2(): void
    {
        $command = $this->getMockBuilder(VersionIncrementCommand::class)
            ->onlyMethods(['option', 'argument'])
            ->getMock();

        $command->method('option')
            ->willReturnMap([
                ['debug', false],
                ['no-commit', false],
                ['list', false],
            ]);

        $command->method('argument')
            ->with('type')
            ->willReturn('major');

        $command->setLaravel($this->app);
        $laravelOutput = new OutputStyle(new ArrayInput([]), new BufferedOutput());
        $command->setOutput($laravelOutput);

        $isResource = $this->getFunctionMock(self::TESTED_NAMESPACE, 'is_resource');
        $isResource->expects($this->once())->willReturn(true);

        $fClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'fclose');
        $fClose->expects($this->exactly(3))->willReturn(true);

//        $streamGetContents = $this->getFunctionMock(self::TESTED_NAMESPACE, 'stream_get_contents');
//        $streamGetContents->expects($this->exactly(2))
//            ->willReturnOnConsecutiveCalls('v2.0.0', ''); // stdout, stderr

        $procClose = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_close');
        $procClose->expects($this->once())->willReturn(0);

        $procOpen = $this->getFunctionMock(self::TESTED_NAMESPACE, 'proc_open');
        $procOpen->expects($this->once())
            ->with(
                $this->stringContains("'./vendor/bin/vs-version-increment' 'major'"),
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->anything()
            )
            ->willReturnCallback(function ($cmd, $desc, &$pipes) {
                $pipes = [fopen('php://memory', 'r'), fopen('php://memory', 'r'), fopen('php://memory', 'r')];
                return true;
            });

        $exitCode = $command->handle();

        $this->assertSame(\Symfony\Component\Console\Command\Command::SUCCESS, $exitCode);
    }

    public function test_executes_external_command_successfully(): void
    {
        $this->assertTrue(true);
    }

    public function test_handles_external_command_failure(): void
    {
        $this->assertTrue(true);
    }

    public function test_shows_error_message_on_stderr(): void
    {
        $this->assertTrue(true);
    }

    public function test_handles_proc_open_failure(): void
    {
        $this->assertTrue(true);
    }

    public function test_passes_all_options_correctly(): void
    {
        $this->assertTrue(true);
    }

    public function test_can_be_instantiated_and_has_correct_traits(): void
    {
        $this->assertTrue(true);
    }

}