# Laravel App Key Rotator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-app-key-rotator.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-app-key-rotator)
![Tests](https://github.com/rawilk/laravel-app-key-rotator/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-app-key-rotator.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-app-key-rotator)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rawilk/laravel-app-key-rotator?style=flat-square)](https://packagist.org/packages/rawilk/laravel-app-key-rotator)
[![License](https://img.shields.io/github/license/rawilk/laravel-app-key-rotator?style=flat-square)](https://github.com/rawilk/laravel-app-key-rotator/blob/main/LICENSE.md)

![Social image](https://banners.beyondco.de/laravel-app-key-rotator.png?theme=light&packageManager=composer+require&packageName=rawilk%2Flaravel-app-key-rotator&pattern=endlessClouds&style=style_1&description=Rotate+app+keys+around+while+re-encrypting+data.&md=1&showWatermark=0&fontSize=100px&images=refresh)

Changing your `APP_KEY` can be as simple as running `php artisan key:generate`, but what about your encrypted model data? This is where Laravel App Key Rotator comes in. This package can help with generating a new app key for you, as well as decrypting and re-encrypting your model automatically for you through an artisan command.

It's also generally a good practice to rotate your app keys periodically (e.g. every 6 months) or when certain events happen, such as an employee leaving the company. See more information here: https://tighten.co/blog/app-key-and-you/

## Basic Usage
Rotating your app keys is as simple as running this artisan command:

```bash
php artisan app-key-rotator:rotate
```

## Documentation
For documentation, please visit: https://randallwilk.dev/docs/laravel-app-key-rotator

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-app-key-rotator
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

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email randall@randallwilk.dev instead of using the issue tracker.

## Credits

- [Randall Wilk](https://github.com/rawilk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
