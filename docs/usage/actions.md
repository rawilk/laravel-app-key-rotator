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
