<?php

declare(strict_types=1);

use Jackiedo\DotenvEditor\DotenvEditor;
use Rawilk\AppKeyRotator\Exceptions\AppKeyNotSetException;

use function Pest\Laravel\artisan;

it('saves the new app key in the env file', function () {
    config([
        'app-key-rotator.actions' => [],
    ]);

    $currentKey = config('app.key');

    artisan('app-key-rotator:rotate');

    $newKey = config('app.key');

    $dotEnv = new DotenvEditor(
        $this->app,
        $this->app['config'],
    );
    $dotEnv->load($this->envPath);

    expect($this->envPath)->toBeReadableFile()
        ->and($dotEnv->getValue('APP_KEY'))->not()->toEqual($currentKey)
        ->and($dotEnv->getValue('APP_KEY'))->toEqual($newKey);

    $envContent = $dotEnv->getContent();

    $this->assertStringContainsString(
        "APP_KEY={$newKey}",
        $envContent,
    );

    $this->assertStringContainsString(
        "OLD_APP_KEY={$currentKey}",
        $envContent,
    );
});

it('throws an exception if no app key is set', function () {
    config(['app.key' => null]);

    artisan('app-key-rotator:rotate');
})->expectException(AppKeyNotSetException::class);
