<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Database\Factories;

use Rawilk\AppKeyRotator\Tests\Models\UserWithAccessors;

class UserWithAccessorsFactory extends UserFactory
{
    /** @var string */
    protected $model = UserWithAccessors::class;
}
