<?php

declare(strict_types=1);

namespace Vasoft\LaravelVersionIncrement\Exceptions;

class ProcessException extends ModuleException
{
    public function __construct(string $message)
    {
        $message = trim($message);
        if (empty($message)) {
            $message = 'Unknow execute error';
        }
        parent::__construct($message);
    }
}
