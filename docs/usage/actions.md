---
title: Actions
sort: 3
---

## Introduction

When the app key is rotated in the artisan command, it can run any actions you define in the `actions` key in the config file.
Any action you define must implement the `Rawilk\AppKeyRotator\Contracts\RotatorAction` interface. the artisan command automatically
passes the package config and the instance of the `Rawilk\AppKeyRotator\AppKeyRotator` class, which is what handles re-encrypting values,
into the `handle()` method of each action it calls.

## Interface

Here is what the RotatorAction interface looks like:

```php
<?php

namespace Rawilk\AppKeyRotator\Contracts;

use Rawilk\AppKeyRotator\AppKeyRotator;

interface RotatorAction
{
    public function handle(AppKeyRotator $appKeyRotator, array $config);
}
```

## Creating an Action

By default, the package has an action for re-encrypting Model values for your models specified in the `models` key.
If you need to modify that behavior, you should extend the `Rawilk\AppKeyRotator\Actions\ReEncryptModels` action and specify it in the config.

If you need to perform other actions, such as re-encrypting data in files on your server, you can create additional actions for them.
Here's an example of a custom action you could create:

```php
<?php

namespace App\Actions\AppKeyRotator;

use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class FileEncrypter implements RotatorAction
{
    public function handle(AppKeyRotator $appKeyRotator, array $config): void
    {
        // $appKeyRotator->reEncrypt('encrypted value');
        // perform your logic here
    }
}
```

## Accepting parameters

Classes that implement the `RotatorAction` interface can accept parameters from the `app-key-rotator` config file.

```php
'actions' => [
    \App\Support\AppKeyRotatorActions\YourAction::class => ['name' => 'value', 'anotherName' => 'value'],
    // other tasks
],
```

In your action, you can accept these parameters via the constructor. Make sure the parameter names matches those used in the config file.

```php
namespace App\Support\AppKeyRotatorActions;

use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class FileEncrypter implements RotatorAction
{
    public function __construct(public string $name, public string $anotherName)
    {
    }

    // ...
}
```

You can also use the constructor to inject dependencies. Just make sure the variable name does not conflict with one of the parameter names in the config file.

```php
namespace App\Support\AppKeyRotatorActions;

use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class FileEncrypter implements RotatorAction
{
    public function __construct(public string $name, public string $anotherName, public MyDependency $myDependency)
    {
    }

    // ...
}
```

### Before Actions

There may be situations where you need to execute an action before the `.env` file is even modified. As of version `3.1.0`, you will be able to define these actions
in the `before_actions` key in the `app-key-rotator` config file. These actions will need to implement the `\Rawilk\AppKeyRotator\Contracts\BeforeRotatorAction`.

Here is the definition of that interface:

```php
<?php

namespace Rawilk\AppKeyRotator\Contracts;

use Rawilk\AppKeyRotator\AppKeyRotator;

interface BeforeRotatorAction
{
    public function handle(array $config);
}
```

It's very similar to the `RotatorAction`, however it only accepts the package config in the `handle` method since the app key will not have been rotated yet. Also, like the regular actions, you may accept parameters in the constructor of your before actions.

## Backup Env Action

As of version `3.1.0` this is a pre-defined action that will run in the `before_actions`. It will create a backup of your `.env` file before our package modifies it. The action accepts a `filename` parameter from the config, so you will be able to customize where that backup is saved to, relative to the root of your application. It is defaulted to `.env.backup`, but you are able to change that name according to your needs.

You can also define the filename in your `.env` file under the `ENV_BACKUP_FILENAME` key if you wish.

```php
'before_actions' => [
    \Rawilk\AppKeyRotator\Actions\BackupEnvAction::class => ['filename' => env('ENV_BACKUP_FILENAME', '.env.backup')],
],
```

> {tip} If you choose to have this action run, it would be a good idea to add the filename to your `.gitignore` file so it doesn't get committed to source control.
