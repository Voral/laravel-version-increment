<?php

namespace Vasoft\Tests\LaravelVersionIncrement;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Vasoft\LaravelVersionIncrement\VersionIncrementServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Vasoft\LaravelVersionIncrement\Commands\VersionIncrementCommand;

class VersionIncrementServiceProviderTest extends TestCase
{
    public function test_registers_version_increment_command_in_console_mode(): void
    {
        // Given: Приложение запущено в консольном режиме (уже установлено в TestCase)

        // When: Загружается сервис-провайдер
        $this->app->register(VersionIncrementServiceProvider::class);

        // Then: Команда должна быть зарегистрирована
        $commands = $this->app->make(Kernel::class)->all();

        $this->assertArrayHasKey('vs-version:increment', $commands);
        $this->assertInstanceOf(VersionIncrementCommand::class, $commands['vs-version:increment']);
    }

}
