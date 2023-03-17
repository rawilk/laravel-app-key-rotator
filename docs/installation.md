---
title: Installation
sort: 3
---

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-app-key-rotator:2.0
```

## Configuration

You should publish the config file:

```bash
php artisan vendor:publish --provider="Rawilk\AppKeyRotator\AppKeyRotatorServiceProvider" --tag="config"
```

This is the default configuration:

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
