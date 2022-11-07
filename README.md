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

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-app-key-rotator
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="app-key-rotator-config"
```

You can view the default configuration file here: https://github.com/rawilk/laravel-app-key-rotator/blob/main/config/app-key-rotator.php

## Documentation
For documentation, please visit: https://randallwilk.dev/docs/laravel-app-key-rotator

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
