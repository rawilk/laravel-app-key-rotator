---
title: Upgrade Guide
sort: 4
---

## Upgrade from v2 to v3

### PHP Version
`laravel-app-key-rotator` now requires a PHP version of at least 8.0. Make sure your environment is running at least that version.

### Laravel Version
Laravel version **9.0** or higher is now required. Upgrade your Laravel installation to at least that version.

### Models/Configuration
The way `laravel-app-key-rotator` discovers which attributes need to be re-encrypted on your models has now changed slightly. In the `models` array in the config, you should now only have a single dimensional array of your model classes that have encrypted properties on them. Each of these models must now implement the `\Rawilk\AppKeyRotator\Contracts\ReEncryptsData` interface and return an array of the model's encrypted properties from the `encryptedProperties` method.

For more information, see: [Models](/docs/laravel-app-key-rotator/{version}/usage/models)

### Actions
All custom actions now must inject the `AppKeyRototor` instance and the package config into the `handle()` method instead of the action's constructor.

```php
namespace App\Actions\AppKeyRotator;

use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class FileEncrypter implements RotatorAction
{
    public function handle(AppKeyRotator $appKeyRotator, array $config): void
    {
    }
}
```

For more information, see: [Actions](/docs/laravel-app-key-rotator/{version}/usage/actions)
