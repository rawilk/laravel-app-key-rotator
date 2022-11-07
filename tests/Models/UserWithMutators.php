<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Models;

use Rawilk\AppKeyRotator\Tests\Database\Factories\UserWithMutatorsFactory;

class UserWithMutators extends UserWithAccessors
{
    protected static function newFactory()
    {
        return new UserWithMutatorsFactory;
    }

    public function setBirthDateAttribute($birthDate): void
    {
        $this->attributes['birth_date'] = $this->encryptValue($birthDate);
    }

    public function setBankAccountAttribute($bankAccount): void
    {
        $this->attributes['bank_account'] = $this->encryptValue($bankAccount);
    }

    protected function encryptValue($value): string
    {
        return encrypt($value);
    }
}
