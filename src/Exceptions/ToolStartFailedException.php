<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Exceptions;

class ToolStartFailedException extends ModuleException
{
    public function __construct()
    {
        parent::__construct('Failed to start vs-version-incrementor');
    }
}
