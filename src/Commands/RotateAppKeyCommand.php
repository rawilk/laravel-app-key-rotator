<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Commands;

use Illuminate\Console\Command;
use Rawilk\AppKeyRotator\Actions\ActionsCollection;
use Rawilk\AppKeyRotator\Actions\BeforeActionsCollection;
use Rawilk\AppKeyRotator\Actions\RotateKeyAction;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\BeforeRotatorAction;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class RotateAppKeyCommand extends Command
{
    public $signature = 'app-key-rotator:rotate';

    public $description = 'Generate a new APP_KEY and re-encrypt database with new key.';

    public function handle(BeforeActionsCollection $beforeActions, ActionsCollection $actions): void
    {
        $this->runBeforeActions($beforeActions);

        $keys = app(RotateKeyAction::class)();

        $appKeyRotator = new AppKeyRotator($keys['old'], $keys['new']);
        $config = config('app-key-rotator');

        $actions->each(function (RotatorAction $action) use ($appKeyRotator, $config) {
            $action->handle($appKeyRotator, $config);
        });

        $this->info(
            "App key was changed from [{$keys['old']}] to [{$keys['new']}]."
        );
    }

    protected function runBeforeActions(BeforeActionsCollection $actions): void
    {
        $config = config('app-key-rotator');

        $actions->each(function (BeforeRotatorAction $action) use ($config) {
            $action->handle($config);
        });
    }
}
