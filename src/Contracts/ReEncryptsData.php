<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Contracts;

interface ReEncryptsData
{
    /**
     * Properties on the model that are encrypted and need to be re-encrypted with
     * a new APP_KEY.
     *
     * @return array<int, string>
     */
    public function encryptedProperties(): array;
}
