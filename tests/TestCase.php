<?php

namespace Rawilk\AppKeyRotator\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Encryption\Encrypter;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\AppKeyRotator\AppKeyRotatorServiceProvider;
use Rawilk\AppKeyRotator\Tests\Models\User;
use Rawilk\AppKeyRotator\Tests\Support\TestEncrypter;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        $this->loadEnvironmentVariables();

        parent::setUp();

        $this->withFactories(__DIR__ . '/database/factories');

        $this->app->useEnvironmentPath(__DIR__ . '/../');

        if (! config('app.key')) {
            $this->artisan('key:generate');
        }

        $this->setupDatabase($this->app);
    }

    protected function getPackageProviders($app): array
    {
        return [
            AppKeyRotatorServiceProvider::class,
        ];
    }

    protected function loadEnvironmentVariables(): void
    {
        if (! file_exists(__DIR__ . '/../.env')) {
            $appKey = 'base64:' . base64_encode(
                Encrypter::generateKey('AES-256-CBC')
            );
            file_put_contents(__DIR__ . '/../.env', 'APP_KEY=' . $appKey);
        }

        $dotEnv = Dotenv::createImmutable(__DIR__ . '/..');

        $dotEnv->load();
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['encrypter'] = new TestEncrypter(
            base64_decode(substr($app['config']['app.key'], 7)),
            $app['config']['app.cipher']
        );
    }

    protected function setupDatabase($app): void
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('birth_date')->nullable();
            $table->string('bank_account')->nullable();
            $table->timestamps();
        });

        factory(User::class, 5)->create();
    }
}
