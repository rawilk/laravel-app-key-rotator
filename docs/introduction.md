---
title: Introduction
sort: 1
---

Changing your `APP_KEY` can be as simple as running `php artisan key:generate`, but what about your encrypted model data?
This is where laravel app key rotator comes in. This package can help with generating a new app key for you, as well
as decrypting and re-encrypting your model automatically for you through an artisan command.

It's also generally a good practice to rotate your app keys periodically (e.g. every 6 months) or when certain events
happen, such as an employee leaving the company. See more information here: [https://tighten.co/blog/app-key-and-you/](https://tighten.co/blog/app-key-and-you/)

Rotating your app keys is as simple as running this artisan command:

```bash
php artisan app-key-rotator:rotate
```

## Disclaimer

This package is not affiliated with, maintained, authorized, endorsed or sponsored by Laravel or any of its affiliates.
