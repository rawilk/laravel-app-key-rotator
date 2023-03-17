# App Key Rotator for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-app-key-rotator.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-app-key-rotator)
![Tests](https://github.com/rawilk/laravel-app-key-rotator/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-app-key-rotator.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-app-key-rotator)

![Social image](https://banners.beyondco.de/laravel-app-key-rotator.png?theme=light&packageManager=composer+require&packageName=rawilk%2Flaravel-app-key-rotator&pattern=endlessClouds&style=style_1&description=Rotate+app+keys+around+while+re-encrypting+data.&md=1&showWatermark=0&fontSize=100px&images=refresh)

Changing your `APP_KEY` can be as simple as running `php artisan key:generate`, but what about your encrypted model data? This is where Laravel App Key Rotator comes in. This package can help with generating a new app key for you, as well as decrypting and re-encrypting your model automatically for you through an artisan command.

It's also generally a good practice to rotate your app keys periodically (e.g. every 6 months) or when certain events happen, such as an employee leaving the company. See more information here: https://tighten.co/blog/app-key-and-you/

## Basic Usage
Rotating your app keys is as simple as running this artisan command:

```bash
php artisan app-key-rotator:rotate
```

## Documentation
For more comprehensive documentation, please visit: https://randallwilk.dev/docs/laravel-app-key-rotator/v1

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-app-key-rotator:1.0
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Rawilk\AppKeyRotator\AppKeyRotatorServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /*
     * This value will be set in the .env file when running the
     * app-key-rotator:rotate command.
     */
    'old_app_key' => env('OLD_APP_KEY'),

    /*
     * List the model classes and the fields that need to be re-encrypted.
     *
     * Example:
     * [
     *     \App\User::class => [
     *          'username',
     *          'date_of_birth',
     *     ],
     * ],
     */
    'models' => [],

    /*
     * When re-encrypting models, this is the chunk size that will be used to help avoid
     * memory limits. Adjust according to your needs.
     */
    'model_chunk_size' => 500,

    /*
     * List any actions here that should be performed when rotating app keys.
     *
     * Each action must implement the \Rawilk\AppKeyRotator\Contracts\RotatorAction interface.
     *
     * Every action receives the package's config and an instance of the AppKeyRotator
     * through the constructor as well.
     */
    'actions' => [
        \Rawilk\AppKeyRotator\Actions\ReEncryptModels::class, # a custom model re-encrypter should extend this class
    ],
];
```

## Usage

Everything is done via the `app-key-rotator:rotate` artisan command. When the command is ran, it'll modify your `.env` file and add `OLD_APP_KEY=your-old-app-key` to it. If something goes wrong with your app key rotation, you can always revert back to your old app key from your `.env` file. This is also used in case decryption fails with the new app key, it'll try and decrypt values with the previous app key. See this for more information: https://gist.github.com/themsaid/ef376d7642be69c1110a0a49b0beb0ea

It's recommended that you should make a backup of your `.env` file before running this command and rotating your app key.

### Models
If you have models that need to have data re-encrypted with the new app key, you can specify them in the `models` key in the config file. Specify the model class, and then an array of fields that are encrypted in the database.

```php
'models' => [
    \App\User::class => [
        'birth_date',
        'bank_account',
    ],
    \App\Student::class => [
        'email',
    ],
],
```

### Actions
When the app key is rotated in the artisan command, it can run any actions you define in the `actions` key in the config file. Any action you define must implement the `\Rawilk\AppKeyRotator\Contracts\RotatorAction` interface. The artisan command automatically passes the package config and the instance of the `\Rawilk\AppKeyRotator\AppKeyRotator` class, which is what handles re-encrypting values into the constructor of each action it calls.

By default, the package has an action for re-encrypting Model values for your models specified in the `models` key. If you need to modify that behavior, you should extend the `Rawilk\AppKeyRotator\Actions\ReEncryptModels` action and specify it in the config.

If you need to perform other actions, such as re-encrypting data in files on your server, you can create additional actions for them.

```php
<?php

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
        // perform your logic here.
    }
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email randall@randallwilk.dev instead of using the issue tracker.

## Credits

- [Randall Wilk](https://github.com/rawilk)
- [All Contributors](../../contributors)

## Disclaimer

This package is not affiliated with, maintained, authorized, endorsed or sponsored by Laravel or any of its affiliates.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
