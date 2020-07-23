<?php

namespace Rawilk\AppKeyRotator\Support;

use Illuminate\Encryption\Encrypter as BaseEncrypter;
use Illuminate\Support\Str;

class Encrypter extends BaseEncrypter
{
    public function decrypt($payload, $unserialize = true)
    {
        try {
            return parent::decrypt($payload, $unserialize);
        } catch (\Throwable $e) {
            $currentKey = $this->key;

            $this->key = Str::startsWith(config('app-key-rotator.old_app_key'), 'base64:')
                ? base64_decode(substr(config('app-key-rotator.old_app_key'), 7))
                : config('app-key-rotator.old_app_key');

            return tap(parent::decrypt($payload, $unserialize), function () use ($currentKey) {
                $this->key = $currentKey;
            });
        }
    }
}
