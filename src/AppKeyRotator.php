<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class AppKeyRotator
{
    protected Encrypter $oldEncrypter;

    protected Encrypter $newEncrypter;

    public function __construct(protected string $oldAppKey = '', protected string $newAppKey = '')
    {
        $this->oldAppKey = $this->normalizeKey($oldAppKey);
        $this->newAppKey = $this->normalizeKey($newAppKey);

        $this->createEncrypters();
    }

    public function reEncrypt($value): string
    {
        return $this->newEncrypter->encrypt($this->oldEncrypter->decrypt($value));
    }

    public function createEncrypters(): void
    {
        $cipher = config('app.cipher');

        if ($this->oldAppKey) {
            $this->oldEncrypter = new Encrypter($this->oldAppKey, $cipher);
        }

        if ($this->newAppKey) {
            $this->newEncrypter = new Encrypter($this->newAppKey, $cipher);
        }
    }

    protected function normalizeKey(string $key): string
    {
        if (Str::startsWith($key, 'base64:')) {
            return base64_decode(substr($key, 7));
        }

        return $key;
    }
}
