<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rawilk\AppKeyRotator\Contracts\ReEncryptsData;
use Rawilk\AppKeyRotator\Tests\Database\Factories\UserFactory;

class User extends Model implements ReEncryptsData
{
    use HasFactory;

    protected static function newFactory()
    {
        return new UserFactory;
    }

    public function encryptedProperties(): array
    {
        return [
            'birth_date',
            'bank_account',
        ];
    }
}
