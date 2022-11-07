<?php

namespace Rawilk\AppKeyRotator\Contracts;

use Rawilk\AppKeyRotator\AppKeyRotator;

interface RotatorAction
{
    public function handle(AppKeyRotator $appKeyRotator, array $config);
}
