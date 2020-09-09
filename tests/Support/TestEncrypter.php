<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Support;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class TestEncrypter extends Encrypter
{
    public function setKey(string $key): self
    {
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $this->key = $key;

        return $this;
    }
}
