<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Actions;

use Illuminate\Support\Collection;

/** @internal */
final class ActionsCollection extends Collection
{
    public function __construct($actionClassNames)
    {
        $actions = collect($actionClassNames)
            ->map(function ($actionParameters, $actionClass) {
                if (is_array($actionParameters) && is_numeric($actionClass)) {
                    $actionClass = array_key_first($actionParameters);
                    $actionParameters = $actionParameters[$actionClass];
                }

                if (is_numeric($actionClass)) {
                    $actionClass = $actionParameters;
                    $actionParameters = [];
                }

                return app()->makeWith($actionClass, $actionParameters);
            })
            ->toArray();

        parent::__construct($actions);
    }
}
