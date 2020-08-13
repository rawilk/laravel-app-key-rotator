---
title: Models
sort: 2
---

If you have models that need to have data re-encrypted with the new app key, you can specify them in the `models` key in the `config/app-key-rotator.php` config file.
Specify the model class, and then an array of fields that are encrypted in the database.

<x-code lang="php">
'models' => [
    \App\User::class => [
        'birth_date',
        'bank_account',
    ],
    \App\Student::class => [
        'email',
    ],
],
</x-code>

## Performance
To help with memory issues that could cause fatal errors in larger databases, the action that re-encrypts your models uses [chunking](https://laravel.com/docs/7.x/eloquent#chunking-results)
to process your records in chunks. The default is `500`, but you can change this to fit your needs in the config file.
