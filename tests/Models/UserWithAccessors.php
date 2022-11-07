<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Models;

use Rawilk\AppKeyRotator\Tests\Database\Factories\UserWithAccessorsFactory;

class UserWithAccessors extends User
{
    protected $table = 'users';

    protected static function newFactory()
    {
        return new UserWithAccessorsFactory;
    }

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
        return $value ? decrypt($value) : $value;
    }
}
