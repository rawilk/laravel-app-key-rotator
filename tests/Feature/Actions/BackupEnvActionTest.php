<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Jackiedo\DotenvEditor\DotenvEditor;
use Rawilk\AppKeyRotator\Actions\BackupEnvAction;

use function Pest\Laravel\artisan;

beforeEach(function () {
    config([
        'app-key-rotator.models' => [],
        'app-key-rotator.actions' => [],
        'app-key-rotator.before_actions' => [
            BackupEnvAction::class => ['filename' => '.env.bak'],
        ],
    ]);

    if (File::exists(base_path('.env.bak'))) {
        File::delete(base_path('.env.bak'));
    }
});

it('backs up the .env file', function () {
    $dotEnv = new DotenvEditor(
        $this->app,
        $this->app['config'],
    );
    $dotEnv->load(base_path('.env'));

    $currentEnvContent = $dotEnv->getContent();

    artisan('app-key-rotator:rotate');

    expect(base_path('.env.bak'))->toBeReadableFile()
        ->and(File::get(base_path('.env.bak')))->toBe($currentEnvContent)
        ->and(File::get($this->envPath))->not()->toBe($currentEnvContent);
});
