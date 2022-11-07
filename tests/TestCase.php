<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Tests;

use Dotenv\Dotenv;
use Illuminate\Encryption\Encrypter;
use Jackiedo\DotenvEditor\DotenvEditorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\AppKeyRotator\AppKeyRotatorServiceProvider;
use Rawilk\AppKeyRotator\Tests\Support\TestEncrypter;

class TestCase extends Orchestra
{
    /**
     * This is where our "app" lives in testbench tests.
     */
    protected string $basePath;

    /**
     * The path to our .env file for our tests.
     */
    protected string $envPath;

    protected function setUp(): void
    {
        $this->basePath = __DIR__ . '/../vendor/orchestra/testbench-core/laravel';
        $this->envPath = "{$this->basePath}/.env";

        $this->loadEnvironmentVariables();

        parent::setUp();

        if (! config('app.key')) {
            $this->artisan('key:generate');
        }
    }

    protected function getPackageProviders($app): array
    {
        return [
            DotenvEditorServiceProvider::class,
            AppKeyRotatorServiceProvider::class,
        ];
    }

    protected function loadEnvironmentVariables(): void
    {
        /*
         * We need to ensure a composer lock file is present in the "base" path so the DotenvEditor package
         * can find it and be able to check for our installed version of the `vlucas/phpdotenv` package.
         */
        file_put_contents("{$this->basePath}/composer.lock", file_get_contents(__DIR__ . '/../composer.lock'));

        if (! file_exists($this->envPath)) {
            $appKey = 'base64:' . base64_encode(
                Encrypter::generateKey('AES-256-CBC')
            );

            file_put_contents($this->envPath, 'APP_KEY=' . $appKey);
        }
    }

    public function getEnvironmentSetUp($app)
    {
        $app['encrypter'] = new TestEncrypter(
            base64_decode(substr($app['config']['app.key'], 7)),
            $app['config']['app.cipher']
        );
    }
}
