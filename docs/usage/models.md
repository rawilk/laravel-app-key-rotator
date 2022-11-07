---
title: Models
sort: 2
---

## Introduction

If you have models that need to have data re-encrypted with the new app key, you can specify them in the `models` key in the `config/app-key-rotator.php` config file.
In each of the models, you will need to implement the `\Rawilk\AppKeyRotator\Contracts\ReEncryptsData` interface, and return an array of encrypted properties on the model
from the `encryptedProperties` method.

```php
'models' => [
    \App\Models\User::class,
    \App\Models\Student::class,
],
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rawilk\AppKeyRotator\Contracts\ReEncryptsData;

class User extends Model implements ReEncryptsData
{
    public function encryptedProperties(): array
    {
        return [
            'birth_date',
            'bank_account',
        ];
    }
}

class Student extends Model implements ReEncryptsData
{
    public function encryptedProperties(): array
    {
        return [
            'email',
        ];
    }
}
```
