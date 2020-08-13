---
title: Actions
sort: 3
---

When the app key is rotated in the artisan command, it can run any actions you define in the `actions` key in the config file.
Any action you define must implement the `Rawilk\AppKeyRotator\Contracts\RotatorAction` interface. the artisan command automatically
passes the package config and the instance of the `Rawilk\AppKeyRotator\AppKeyRotator` class, which is what handles re-encrypting values,
into the constructor of each action it calls.

Here is what the RotatorAction interface looks like:

<x-code lang="php">
namespace Rawilk\AppKeyRotator\Contracts;

interface RotatorAction
{
    public function handle();
}
</x-code>

By default the package has an action for re-encrypting Model values for your models specified in the `models` key.
If you need to modify that behavior, you should extend the `Rawilk\AppKeyRotator\Actions\ReEncryptModels` action and specify it in the config.

If you need to perform other actions, such as re-encrypting data in files on your server, you can create additional actions for them.
Here's an example of a custom action you could create:

<x-code lang="php">
namespace App\Actions\AppKeyRotator;

use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class FileEncrypter implements RotatorAction
{
    protected AppKeyRotator $appKeyRotator;

    public function __construct(array $config, AppKeyRotator $appKeyRotator)
    {
        $this->appKeyRotator = $appKeyRotator;
    }

    public function handle()
    {
        // $this->appKeyRotator->reEncrypt('encrypted value');
        // perform your logic here
    }
}
</x-code>
