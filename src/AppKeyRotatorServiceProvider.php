<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Rawilk\AppKeyRotator\Actions\ActionsCollection;
use Rawilk\AppKeyRotator\Actions\BeforeActionsCollection;
use Rawilk\AppKeyRotator\Actions\RotateKeyAction;
use Rawilk\AppKeyRotator\Commands\RotateAppKeyCommand;
use Rawilk\AppKeyRotator\Support\Encrypter;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/** @internal */
final class AppKeyRotatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-app-key-rotator')
            ->hasConfigFile()
            ->hasCommands([
                RotateAppKeyCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        // We'll use our custom Encrypter to allow for decrypting with the previous app key in case
        // using the current key fails.
        $this->app->singleton('encrypter', function () {
            $config = Container::getInstance()->make('config')['app'];

            if (Str::startsWith($key = $config['key'], 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            return new Encrypter($key, $config['cipher']);
        });

        // The rest of our bindings are only needed in the console.
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->app->singleton(ActionsCollection::class, function ($app) {
            $actionClassNames = $app['config']->get('app-key-rotator.actions', []) ?? [];

            return new ActionsCollection($actionClassNames);
        });

        $this->app->singleton(BeforeActionsCollection::class, function ($app) {
            $actionClassNames = $app['config']->get('app-key-rotator.before_actions', []) ?? [];

            return new BeforeActionsCollection($actionClassNames);
        });

        $this->app->singleton(RotateKeyAction::class, function ($app) {
            return new RotateKeyAction($app['dotenv-editor']);
        });
    }
}
