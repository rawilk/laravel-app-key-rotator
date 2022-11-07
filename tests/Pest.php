<?php

declare(strict_types=1);

use Rawilk\AppKeyRotator\Tests\Models\User;
use Rawilk\AppKeyRotator\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// Helpers

function setUpDatabase(): void
{
    $migration = include __DIR__ . '/database/migrations/create_test_tables.php';
    $migration->up();

    // User::factory()->count(5)->create();
}
