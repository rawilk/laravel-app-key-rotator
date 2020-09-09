<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Rawilk\AppKeyRotator\Tests\Models\User;

class UserFactory extends Factory
{
    /** @var string */
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'birth_date' => Crypt::encrypt($this->faker->dateTime),
            'bank_account' => Crypt::encrypt($this->faker->bankAccountNumber),
        ];
    }
}
