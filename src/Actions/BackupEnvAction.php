<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Actions;

use Rawilk\AppKeyRotator\Contracts\BeforeRotatorAction;

class BackupEnvAction implements BeforeRotatorAction
{
    public function __construct(protected string $filename)
    {
    }

    public function handle(array $config)
    {
        file_put_contents(
            base_path($this->filename),
            file_get_contents(base_path('.env')),
        );
    }
}
