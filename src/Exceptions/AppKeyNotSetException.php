<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Exceptions;

use Exception;

class AppKeyNotSetException extends Exception
{
    public static function keyNotSet(): self
    {
        return new static('APP_KEY not set in .env file. Generate an application encryption key before running this command.');
    }
}
