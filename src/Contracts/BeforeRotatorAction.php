<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Contracts;

interface BeforeRotatorAction
{
    public function handle(array $config);
}
