# Laravel App Key Rotator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-app-key-rotator.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-app-key-rotator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/rawilk/laravel-app-key-rotator/run-tests?label=tests)](https://github.com/rawilk/laravel-app-key-rotator/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-app-key-rotator.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-app-key-rotator)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-app-key-rotator
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Rawilk\Skeleton\SkeletonServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Rawilk\Skeleton\SkeletonServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
$skeleton = new Rawilk\Skeleton;
echo $skeleton->echoPhrase('Hello, Rawilk!');
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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
