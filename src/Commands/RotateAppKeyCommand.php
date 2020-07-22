<?php

namespace Rawilk\AppKeyRotator\Commands;

use Illuminate\Console\Command;

class RotateAppKeyCommand extends Command
{
    public $signature = 'app-key-rotator:rotate';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
