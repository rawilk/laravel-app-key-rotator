<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Commands;

use Illuminate\Console\Command;
use Jackiedo\DotenvEditor\DotenvEditor;
use Rawilk\AppKeyRotator\AppKeyRotator;

class RotateAppKeyCommand extends Command
{
    public $signature = 'app-key-rotator:rotate';

    public $description = 'My command';

    protected ?string $oldAppKey;
    protected string $newAppKey;
    protected DotenvEditor $dotEnv;
    protected AppKeyRotator $appKeyRotator;

    public function handle(): void
    {
        $this->setDotEnvEditor();

        $this->setKeys();
        $this->executeActions();

        $this->info(
            "App key was changed from [{$this->oldAppKey}] to [{$this->newAppKey}]."
        );
    }

    protected function getCurrentKey(): string
    {
        $currentKey = $this->laravel['config']['app.key'];

        $this->setKeyInEnvironmentFile(
            $currentKey,
            'OLD_APP_KEY',
            'Rotated at: ' . now()->toDateTimeString()
        );

        // Update the value in the config.
        $this->laravel['config']['app-key-rotator.old_app_key'] = $currentKey;

        return $currentKey;
    }

    protected function setKeyInEnvironmentFile($key, string $envKey, string $comment = null): void
    {
        $this->dotEnv->setKey($envKey, $key, $comment);

        $this->dotEnv->save();
    }

    protected function setDotEnvEditor(): void
    {
        $this->laravel['config']['dotenv-editor.autobackup'] = false;

        $this->dotEnv = new DotenvEditor($this->laravel['app'], $this->laravel['config']);

        $this->dotEnv->autoBackup(false);
    }

    protected function setKeys(): void
    {
        $this->oldAppKey = $this->getCurrentKey();

        $this->call('key:generate', [
            '--force' => true,
        ]);

        $this->newAppKey = $this->laravel['config']['app.key'];
    }

    protected function executeActions(): void
    {
        $this->appKeyRotator = new AppKeyRotator($this->oldAppKey, $this->newAppKey);

        foreach ($this->laravel['config']['app-key-rotator.actions'] ?? [] as $action) {
            (new $action($this->laravel['config']['app-key-rotator'], $this->appKeyRotator))->handle();
        }
    }
}
