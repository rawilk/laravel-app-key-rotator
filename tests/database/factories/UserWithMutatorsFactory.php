<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Database\Factories;

use Rawilk\AppKeyRotator\Tests\Models\UserWithMutators;

class UserWithMutatorsFactory extends UserFactory
{
    /** @var string */
    protected $model = UserWithMutators::class;
}
