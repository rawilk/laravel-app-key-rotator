<?php

namespace Rawilk\AppKeyRotator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Rawilk\AppKeyRotator\Commands\RotateAppKeyCommand;
use Rawilk\AppKeyRotator\Support\Encrypter;

class AppKeyRotatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/app-key-rotator.php', 'app-key-rotator');

        // We'll use our custom Encrypter to allow for decrypting with the previous app key in case
        // using the current key fails.
        $this->app->singleton('encrypter', static function ($app) {
            $config = $app->make('config')->get('app');

            if (Str::startsWith($key = $config['key'], 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            return new Encrypter($key, $config['cipher']);
        });
    }

    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__ . '/../config/app-key-rotator.php' => config_path('app-key-rotator.php'),
        ], 'config');

        $this->commands([
            RotateAppKeyCommand::class,
        ]);
    }
}
