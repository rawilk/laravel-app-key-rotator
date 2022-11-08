<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Old App Key
    |--------------------------------------------------------------------------
    |
    | This value will be set in the .env file when running the
    | app-key-rotator:rotate command.
    |
    */
    'old_app_key' => env('OLD_APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Include any models here that have fields that need to be re-encrypted.
    | Each model class must implement the \Rawilk\AppKeyRotator\Contracts\ReEncryptsData
    | interface.
    |
    */
    'models' => [],

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    |
    | Any actions here will be run after a new app key has been generated and
    | saved to your .env file.
    |
    | Each action must implement the \Rawilk\AppKeyRotator\Contracts\RotatorAction interface.
    |
    | Every action receives the package's config and an instance of the AppKeyRotator
    | through the `handle` method.
    |
    */
    'actions' => [
        \Rawilk\AppKeyRotator\Actions\ReEncryptModels::class, // a custom model re-encrypter should extend this class
    ],

    /*
    |--------------------------------------------------------------------------
    | Before Actions
    |--------------------------------------------------------------------------
    |
    | Any actions here will be run BEFORE anything else when rotating the app
    | key. Can be useful if you want to automate something like backing
    | up the .env file first.
    |
    | Each action must implement the \Rawilk\AppKeyRotator\Contracts\BeforeRotatorAction interface.
    |
    | Every action receives the package's config through the `handle` method.
    |
    */
    'before_actions' => [
        // \Rawilk\AppKeyRotator\Actions\BackupEnvAction::class => ['filename' => env('ENV_BACKUP_FILENAME', '.env.backup')]
    ],
];
