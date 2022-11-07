<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rawilk\AppKeyRotator\Tests\Models\User;

class UserFactory extends Factory
{
    /** @var string */
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'birth_date' => encrypt(fake()->dateTime()),
            'bank_account' => encrypt(fake()->creditCardNumber()),
        ];
    }
}
