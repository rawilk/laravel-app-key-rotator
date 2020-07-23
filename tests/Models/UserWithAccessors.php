<?php

namespace Rawilk\AppKeyRotator\Tests\Models;

use Illuminate\Support\Facades\Crypt;

class UserWithAccessors extends User
{
    protected $table = 'users';

    public function getBirthDateAttribute($birthDate)
    {
        return $this->decryptValue($birthDate);
    }

    public function getBankAccountAttribute($bankAccount)
    {
        return $this->decryptValue($bankAccount);
    }

    protected function decryptValue($value)
    {
        return $value ? Crypt::decrypt($value) : $value;
    }
}
