<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Actions;

use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\DotenvEditor;
use Rawilk\AppKeyRotator\Exceptions\AppKeyNotSetException;

/** @internal */
final class RotateKeyAction
{
    public function __construct(private DotenvEditor $env)
    {
        config([
            'dotenv-editor.autoBackup' => false,
        ]);

        $this->env->autoBackup(false);
    }

    /**
     * @return array<string, string>
     */
    public function __invoke(): array
    {
        $currentKey = $this->getCurrentKey();

        $this->writeToEnv(
            'OLD_APP_KEY',
            $currentKey,
            'Rotated at: ' . now()->toDateTimeString() . ' ' . now()->tzName,
        );

        config([
            'app-key-rotator.old_app_key' => $currentKey,
        ]);

        Artisan::call('key:generate', [
            '--force' => true,
        ]);

        return [
            'old' => $currentKey,
            'new' => config('app.key'),
        ];
    }

    private function getCurrentKey(): string
    {
        $key = config('app.key');

        throw_unless($key, AppKeyNotSetException::keyNotSet());

        return $key;
    }

    private function writeToEnv(string $key, $value, ?string $comment = null): void
    {
        $this->env->setKey($key, $value, $comment)->save();
    }
}
