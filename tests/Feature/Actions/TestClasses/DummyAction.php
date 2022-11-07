<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests\Feature\Actions\TestClasses;

use Illuminate\Cache\Repository;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class DummyAction implements RotatorAction
{
    public function __construct(
        public Repository $cache,
        public int $a = 0,
        public int $b = 0,
    ) {
    }

    public function handle(AppKeyRotator $appKeyRotator, array $config)
    {
    }
}
