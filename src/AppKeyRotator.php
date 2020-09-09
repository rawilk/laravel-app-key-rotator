<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class AppKeyRotator
{
    protected string $oldAppKey;
    protected string $newAppKey;
    protected Encrypter $oldEncrypter;
    protected Encrypter $newEncrypter;

    public function __construct(string $oldAppKey = '', string $newAppKey = '')
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
            $this->oldEncrypter = new Encrypter((string) $this->oldAppKey, $cipher);
        }

        if ($this->newAppKey) {
            $this->newEncrypter = new Encrypter((string) $this->newAppKey, $cipher);
        }
    }

    protected function normalizeKey(string $key): string
    {
        if (Str::startsWith($key, 'base64:')) {
            return base64_decode(substr($key, 7));
        }

        return $key;
    }

    /*
     * These methods are for testing purposes.
     */

    public function setOldAppKey(string $oldAppKey): self
    {
        $this->oldAppKey = $this->normalizeKey($oldAppKey);

        return $this;
    }

    public function setNewAppKey(string $newAppKey): self
    {
        $this->newAppKey = $this->normalizeKey($newAppKey);

        return $this;
    }

    public function oldEncrypter(): Encrypter
    {
        return $this->oldEncrypter;
    }

    public function newEncrypter(): Encrypter
    {
        return $this->newEncrypter;
    }
}
