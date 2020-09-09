<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Models;

use Illuminate\Support\Facades\Crypt;

class UserWithMutators extends UserWithAccessors
{
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
        return Crypt::encrypt($value);
    }
}
