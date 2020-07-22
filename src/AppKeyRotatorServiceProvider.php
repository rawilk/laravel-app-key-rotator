<?php

namespace Rawilk\AppKeyRotator;

use Illuminate\Support\ServiceProvider;
use Rawilk\AppKeyRotator\Commands\RotateAppKeyCommand;

class AppKeyRotatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/app-key-rotator.php' => config_path('app-key-rotator.php'),
            ], 'config');

            $this->commands([
                RotateAppKeyCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/app-key-rotator.php', 'app-key-rotator');
    }
}
